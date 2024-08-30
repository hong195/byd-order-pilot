<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order;

use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\ValueObject\Status;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class UnassignOrder
{
    /**
     * Class construction.
     */
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }

    /**
     * Handle method.
     *
     * @param int $orderId the ID of the order to be handled
     *
     * @throws NotFoundHttpException if the order is not found
     */
    public function handle(int $orderId): void
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new NotFoundHttpException('Order not found');
        }

        $order->changeStatus(Status::UNASSIGNED);

        $order->changeSortOrder(null);

        $this->orderRepository->save($order);
    }
}
