<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order;

use App\Orders\Domain\Exceptions\OrderReprintException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Orders\Domain\ValueObject\Process;
use App\Orders\Domain\ValueObject\Status;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ChangeSortOrder.
 *
 * Handles the sorting of orders for a specific roll.
 */
final readonly class ReprintOrder
{
    /**
     * Example class constructor.
     *
     * @param RollRepositoryInterface  $rollRepository  The roll repository interface
     * @param OrderRepositoryInterface $orderRepository The order repository interface
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private OrderRepositoryInterface $orderRepository)
    {
    }

    /**
     * Handle the order reprint.
     *
     * @param int $rollId The ID of the roll
     *
     * @throws NotFoundHttpException If the roll with the specified ID is not found
     * @throws OrderReprintException
     */
    public function handle(int $rollId, int $orderId): void
    {
        $roll = $this->rollRepository->findById($rollId);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        if ($roll->getProcess()->equals(Process::ORDER_CHECK_IN)) {
            throw new OrderReprintException('Roll is not in the correct process');
        }

        if ($roll->getOrders()->isEmpty()) {
            throw new NotFoundHttpException('No orders found!');
        }

        foreach ($roll->getOrders() as $order) {
            if ($order->getId() === $orderId) {
                $order->changeStatus(Status::ASSIGNABLE);
                $order->updateHasPriority(true);

                $this->orderRepository->save($order);

                return;
            }
        }

        throw new NotFoundHttpException('Order not found');
    }
}
