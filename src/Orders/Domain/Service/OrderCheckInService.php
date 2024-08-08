<?php

namespace App\Orders\Domain\Service;

use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\RollFilter;
use App\Orders\Infrastructure\Repository\RollRepository;
use App\Shared\Domain\Service\AssertService;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class MaxMinReArrangeOrderService.
 */
final readonly class OrderCheckInService implements OrderCheckInInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private SortOrdersServiceInterface $sortOrdersService,
        private RollRepository $rollRepository
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
        $rolls = $this->rollRepository->findQueried($rollFilter);

        $orders = new ArrayCollection([$addedOrder]);

        $orders = $this->sortOrdersService->getSorted($orders)->toArray();

        $orderStacks = [];
        $unassignOrders = [];

        //        foreach ($rolls as $roll) {
        //            $orderStacks[] = $this->makeOrderStack($addedOrder->getRollType()->value, $roll->getLength(), $addedOrder->getLaminationType()?->value);
        //        }

        while (!empty($orders)) {
            $order = array_shift($orders);
            $orderAdded = false;

            //            foreach ($orderStacks as $orderStack) {
            //                if ($orderStack->canAddOrder($order)) {
            //                    $orderStack->addOrder($order);
            //                    $orderAdded = true;
            //                    break;
            //                }
            //            }
            //
            //            if (!$orderAdded) {
            //                $unassignOrders[] = $order;
            //            }
        }

        //  $unassignOrdersLength = array_sum(array_map(fn ($order) => $order->getLength(), $unassignOrders));
        //        $orderStackForUnassignOrders = $this->makeOrderStack($addedOrder->getRollType()->value, $unassignOrdersLength, $addedOrder->getLaminationType()?->value);
        //
        //        foreach ($unassignOrders as $order) {
        //            $orderStackForUnassignOrders->addOrder($order);
        //        }
        //
        //        // saving new formed orders stack
        //        foreach ([...$orderStacks, $orderStackForUnassignOrders] as $orderStack) {
        //            if ($orderStack->getOrders()->isEmpty()) {
        //                continue;
        //            }
        //
        //            ($this->assignPrinterService)($orderStack);
        //        }
    }
}
