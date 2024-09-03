<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order\Pack;

use App\Orders\Domain\Exceptions\CantPackMainProductException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\ValueObject\Status;

final readonly class PackMainProduct
{
    public function __construct(private FindOrderForPacking $orderForPacking, private OrderRepositoryInterface $orderRepository)
    {
    }

    /**
     * @throws CantPackMainProductException
     */
    public function handle(int $rollId, int $orderId): void
    {
        $order = $this->orderForPacking->getOrder($rollId, $orderId);

        $order->setPack(true);
        $order->changeStatus(Status::SHIP_AND_COLLECT);

        $this->orderRepository->save($order);
    }
}
