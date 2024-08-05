<?php

namespace App\Orders\Domain\Service;

use App\Orders\Domain\Aggregate\OrderStack\OrderStack;
use App\Orders\Domain\Factory\OrderStackFactory;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\OrderStackFilter;
use App\Orders\Domain\Repository\OrderStackRepositoryInterface;
use App\Orders\Domain\Repository\RollFilter;
use App\Orders\Infrastructure\Repository\RollRepository;
use App\Shared\Domain\Service\AssertService;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class MaxMinReArrangeOrderService.
 */
final readonly class OrderCheckInService implements OrderCheckInInterface
{
    /**
     * Initializes a new instance of the class.
     *
     * @param OrderStackRepositoryInterface $orderStackRepository the order stack repository
     * @param OrderRepositoryInterface      $orderRepository      the order repository
     * @param OrderStackFactory             $orderStackFactory    the order stack factory
     */
    public function __construct(
        private OrderStackRepositoryInterface $orderStackRepository,
        private OrderRepositoryInterface $orderRepository,
        private OrderStackFactory $orderStackFactory,
        private SortOrdersServiceInterface $sortOrdersService,
        private RollRepository $rollRepository,
    ) {
    }

    /**
     * Re-arranges the order by adding it to an existing stack or creating a new stack.
     *
     * @param int $orderId the ID of the order to be rearranged
     */
    public function checkIn(int $orderId): void
    {
        $addedOrder = $this->orderRepository->findById($orderId);

        AssertService::notEmpty($addedOrder, 'Orders not found');

        $rollFilter = new RollFilter($addedOrder->getRollType()->value);
        $orderStackFilter = new OrderStackFilter($addedOrder->getRollType()->value, $addedOrder->getLaminationType()?->value);

        $existingOrderStacks = $this->orderStackRepository->findQueried($orderStackFilter);
        $rolls = $this->rollRepository->findQueried($rollFilter);

        $orders = new ArrayCollection([$addedOrder]);

        foreach ($existingOrderStacks as $orderStack) {
            foreach ($orderStack->getOrders() as $order) {
                $orders->add($order);
            }
            $this->orderStackRepository->remove($orderStack);
        }

        $orders = $this->sortOrdersService->getSorted($orders)->toArray();

        $orderStacks = [];
        $unassignOrders = [];

        foreach ($rolls as $roll) {
            $orderStacks[] = $this->makeOrderStack($addedOrder->getRollType()->value, $roll->getLength(), $addedOrder->getLaminationType()?->value);
        }

        while (!empty($orders)) {
            $order = array_shift($orders);
            $orderAdded = false;

            foreach ($orderStacks as $orderStack) {
                if ($orderStack->canAddOrder($order)) {
                    $orderStack->addOrder($order);
                    $orderAdded = true;
                    break;
                }
            }

            if (!$orderAdded) {
                $unassignOrders[] = $order;
            }
        }

        $unassignOrdersLength = array_sum(array_map(fn ($order) => $order->getLength(), $unassignOrders));
        $orderStackForUnassignOrders = $this->makeOrderStack($addedOrder->getRollType()->value, $unassignOrdersLength, $addedOrder->getLaminationType()?->value);

        foreach ($unassignOrders as $order) {
            $orderStackForUnassignOrders->addOrder($order);
        }

        // saving new formed orders stack
        foreach ([...$orderStacks, $orderStackForUnassignOrders] as $orderStack) {
            if ($orderStack->getOrders()->isEmpty()) {
                continue;
            }

            $this->orderStackRepository->save($orderStack);
        }
    }

    /**
     * Makes an order stack.
     *
     * @param string      $rollType       the roll type
     * @param int         $length         the length
     * @param string|null $laminationType the lamination type
     *
     * @return OrderStack the created order stack
     */
    private function makeOrderStack(string $rollType, int $length = 0, ?string $laminationType = null): OrderStack
    {
        return $this->orderStackFactory->make(
            name: "Order Stack with Roll $rollType",
            length: $length,
            rollType: $rollType,
            laminationType: $laminationType,
        );
    }
}
