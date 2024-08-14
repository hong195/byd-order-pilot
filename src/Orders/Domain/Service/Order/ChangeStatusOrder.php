<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order;

use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\ValueObject\Status;
use App\Shared\Domain\Service\AssertService;

final readonly class ChangeStatusOrder
{
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }

    public function handle(int $id, Status $status): void
    {
        $order = $this->orderRepository->findById($id);

        AssertService::notNull($order, 'Order not found.');

        $order->changeStatus($status);

        $this->orderRepository->save($order);
    }
}
