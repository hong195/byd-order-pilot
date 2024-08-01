<?php

namespace App\Orders\Domain\Service;

use App\Orders\Domain\Aggregate\Order\Order;
use App\Orders\Domain\Aggregate\OrderStack\OrderStack;
use App\Orders\Domain\Factory\OrderStackFactory;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\OrderStackFilter;
use App\Orders\Domain\Repository\OrderStackRepositoryInterface;
use App\Shared\Domain\Service\AssertService;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class MaxMinReArrangeOrderService.
 */
final readonly class DefaultOrderCheckInService implements OrderCheckInInterface
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

        $orderStackFilter = new OrderStackFilter($addedOrder->getRollType()->value, $addedOrder->getLaminationType()?->value);
        $orderStacks = $this->orderStackRepository->findQueried($orderStackFilter);

        if (empty($orderStacks)) {
            $orderStack = $this->makeOrderStack($addedOrder);
            $orderStack->addOrder($addedOrder);

            $this->orderStackRepository->save($orderStack);

            return;
        }

        $orders = new ArrayCollection([$addedOrder]);

        foreach ($orderStacks as $orderStack) {
            foreach ($orderStack->getOrders() as $order) {
                $orders->add($order);
            }
        }

        $orders = $this->sortOrdersService->getSorted($orders)->toArray();

        $this->removeOrdersFromOrderStacks($orderStacks);

        $formedOrderStacks = [];

        while (!empty($orderStacks) || !empty($orders)) {
            if (empty($orderStacks)) {
                $orderStack = $this->makeOrderStack($orders[0]);
                $orderStacks[] = $orderStack;
            }

            $currentOrderStack = array_shift($orderStacks);

            while (!empty($orders)) {
                $currentOrder = array_shift($orders);

                if ($currentOrderStack->canAddOrder($currentOrder)) {
                    $currentOrderStack->addOrder($currentOrder);
                } else {
                    array_unshift($orders, $currentOrder);
                    break;
                }
            }

            $formedOrderStacks[] = $currentOrderStack;
        }

        // saving new formed orders stack
        foreach ($formedOrderStacks as $orderStack) {
            $this->orderStackRepository->save($orderStack);
        }
    }

    /**
     * Makes empty order stacks.
     *
     * @param OrderStack[] $orderStacks the order stacks
     */
    private function removeOrdersFromOrderStacks(array $orderStacks): void
    {
        array_map(function (OrderStack $orderStack) {
            $orderStack->removeOrders();
        }, $orderStacks);
    }

    private function createInfinityOrderStack()
    {
    }

    /**
     * Creates an order stack for the given order.
     *
     * @param Order $order The order object
     *
     * @return OrderStack The created order stack
     */
    private function makeOrderStack(Order $order): OrderStack
    {
        return $this->orderStackFactory->make(
            name: $order->getRollType()->value,
            rollType: $order->getRollType()->value,
            length: 100,
            priority: 1,
            laminationType: $order->getLaminationType()?->value,
        );
    }
}
