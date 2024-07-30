<?php

namespace App\Rolls\Domain\Service;

use App\Rolls\Domain\Aggregate\Order\Order;
use App\Rolls\Domain\Aggregate\OrderStack\OrderStack;
use App\Rolls\Domain\Factory\OrderStackFactory;
use App\Rolls\Domain\Repository\OrderRepositoryInterface;
use App\Rolls\Domain\Repository\OrderStackFilter;
use App\Rolls\Domain\Repository\OrderStackRepositoryInterface;
use App\Shared\Domain\Service\AssertService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class MaxMinReArrangeOrderService.
 */
final readonly class MaxMinOrderCheckInService implements OrderCheckInInterface
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

        AssertService::notEmpty($addedOrder, 'Order not found');

        $orderStackFilter = new OrderStackFilter($addedOrder->getRollType()->value, $addedOrder->getLaminationType()?->value);
        $orderStacks = $this->orderStackRepository->findQueried($orderStackFilter);

        if (empty($orderStacks)) {
            $orderStack = $this->makeOrderStack($addedOrder);
            $orderStack->addOrder($addedOrder);

            $this->orderStackRepository->save($orderStack);

            return;
        }

        $orders = $this->getSortedOrdersFromOrderStacks($orderStacks);

        $this->removeOrdersFromOrderStacks($orderStacks);

        $formedOrderStacks = [];

        foreach ($orders as $order) {
            if (empty($formedOrderStacks) || !$formedOrderStacks[count($formedOrderStacks) - 1]->canAddOrder($order)) {
                $orderStack = $this->makeOrderStack($order);
                $formedOrderStacks[] = $orderStack;
            }

            $formedOrderStacks[count($formedOrderStacks) - 1]->addOrder($order);
        }

        // saving new formed orders stack into database
        foreach ($formedOrderStacks as $orderStack) {
            $this->orderStackRepository->save($orderStack);
        }
    }

    /**
     * Sorts the given order stacks.
     *
     * @param OrderStack[] $orderStacks the order stacks to be sorted
     *
     * @return Collection the sorted order stacks
     */
    private function getSortedOrdersFromOrderStacks(array $orderStacks): Collection
    {
        $orders = [];
        foreach ($orderStacks as $orderStack) {
            foreach ($orderStack->getOrders() as $order) {
                $orders[] = $order;
            }
        }

        return $this->sortOrdersService->getSorted(new ArrayCollection($orders));
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
