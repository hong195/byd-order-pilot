<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ChangeSortOrder.
 *
 * Handles the sorting of orders for a specific roll.
 */
final readonly class ChangeSortOrder
{
    /**
     * Example class constructor.
     *
     * @param OrderRepositoryInterface $orderRepository The order repository interface
     */
    public function __construct(private OrderRepositoryInterface $orderRepository, private GroupService $groupService)
    {
    }

    /**
     * Handle the roll update.
     *
     * @param int   $rollId     The ID of the roll
     * @param int   $group      The group number the orders by lamination
     * @param int[] $sortOrders The sort order to apply to the orders
     *
     * @throws NotFoundHttpException If the roll with the specified ID is not found
     */
    public function handle(int $rollId, int $group, array $sortOrders): void
    {
        //        $roll = $this->rollRepository->findById($rollId);
        //
        //        if (!$roll) {
        //            throw new NotFoundHttpException('Roll not found');
        //        }

        //        $ordersGropedByLamination = $this->groupService->handle($roll->getOrders());
        //
        //        if (!isset($ordersGropedByLamination[$group])) {
        //            throw new NotFoundHttpException('Group not found');
        //        }
        //
        //        $groupOrders = $ordersGropedByLamination[$group];
        //
        //        foreach ($sortOrders as $orderId => $sortOrder) {
        //            /** @var Order|false $order */
        //            $order = $groupOrders->filter(fn (Order $order) => $order->getId() === $orderId)->first();
        //
        //            if (!$order) {
        //                throw new NotFoundHttpException('Order not found');
        //            }
        //
        //            $order->changeSortOrder($sortOrder);
        //            $this->orderRepository->save($order);
        //        }
    }
}
