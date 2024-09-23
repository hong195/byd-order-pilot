<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order;

use App\Orders\Domain\Exceptions\OrderReprintException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Service\Roll\OrdersCheckInProcess\OrdersCheckInInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReprintOrder.
 *
 * This class handles the printing of orders.
 */
final readonly class ReprintOrder
{
    /**
     * Example class constructor.
     *
     * @param OrderRepositoryInterface $orderRepository The order repository interface
     */
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }

    /**
     * Handle the order reprint.
     *
     * @throws NotFoundHttpException If the roll with the specified ID is not found
     * @throws OrderReprintException
     */
    public function handle(int $orderId): void
    {
//        $order = $this->orderRepository->findById($orderId);
//
//        if (!$order) {
//            throw new NotFoundHttpException('Order not found');
//        }
//
//        $order->reprint();
//
//        $this->orderRepository->save($order);
//
//        $this->ordersCheckIn->checkIn();
    }
}
