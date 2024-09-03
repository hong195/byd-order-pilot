<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order\Pack;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Exceptions\CantPackMainProductException;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Orders\Domain\ValueObject\Process;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class FindOrderForPacking
{
    public function __construct(private RollRepositoryInterface $rollRepository)
    {
    }

    /**
     * @throws CantPackMainProductException
     */
    public function getOrder(int $rollId, int $orderId): Order
    {
        $roll = $this->rollRepository->findById($rollId);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        if (!$roll->getProcess()->equals(Process::CUTTING_CHECK_IN)) {
            throw new CantPackMainProductException('Roll is not in the cutting check-in process');
        }

        /** @var Order $order */
        $order = $roll->getOrders()->filter(fn (Order $order) => $order->getId() === $orderId)->first();

        if (!$order) {
            throw new NotFoundHttpException('Order not found');
        }

        return $order;
    }
}
