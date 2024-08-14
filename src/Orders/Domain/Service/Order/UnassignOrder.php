<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order;

use App\Orders\Domain\Exceptions\OrderCantBeUnassignedException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\ValueObject\Status;

final readonly class UnassignOrder
{
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }

    /**
     * @throws OrderCantBeUnassignedException
     */
    public function handle(int $id): void
    {
        $order = $this->orderRepository->findById($id);

        if ($order->getStatus()->unassignable()) {
            throw new OrderCantBeUnassignedException('Order can not be unassigned');
        }

        $order->changeStatus(Status::UNASSIGNED);

        $this->orderRepository->save($order);
    }
}
