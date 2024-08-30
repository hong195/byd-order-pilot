<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service;

use App\Orders\Domain\Exceptions\OrderReprintException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Orders\Domain\Service\OrdersCheckInProcess\OrdersCheckInInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReprintOrder.
 *
 * This class handles the printing of orders.
 */
final readonly class ReprintRoll
{
    /**
     * Example class constructor.
     *
     * @param OrderRepositoryInterface $orderRepository The order repository interface
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private OrderRepositoryInterface $orderRepository, private OrdersCheckInInterface $ordersCheckIn)
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
    public function handle(int $rollId): void
    {
        $roll = $this->rollRepository->findById($rollId);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        foreach ($roll->getOrders() as $order) {
            $order->reprint();
            $this->orderRepository->save($order);
        }

        $this->rollRepository->remove($roll);

        $this->ordersCheckIn->checkIn();
    }
}
