<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order\Pack;

use App\Orders\Domain\Exceptions\CantPackMainProductException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\ValueObject\Status;

final readonly class UnPackMainProduct
{
    public function __construct(private OrderRepositoryInterface $orderRepository, private FindOrderForPacking $orderForPacking)
    {
    }

    /**
     * @throws CantPackMainProductException
     */
    public function handle(int $rollId, int $orderId): void
    {
        $order = $this->orderForPacking->getOrder($rollId, $orderId);

        $order->setPack(false);
        $order->changeStatus(Status::ASSIGNED);

        $this->orderRepository->save($order);
    }
}
