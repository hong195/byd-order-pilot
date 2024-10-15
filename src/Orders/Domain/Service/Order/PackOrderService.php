<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order;

use App\Orders\Domain\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class PackOrderService
{
    /**
     * Constructor for initializing OrderService.
     */
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }

    /**
     * Handle the packing process for an order.
     *
     * @param int $orderId The ID of the order to be packed
     *
     * @throws NotFoundHttpException If the order is not found
     */
    public function handle(int $orderId): void
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new NotFoundHttpException('Order not found');
        }

        $products = $order->getProducts();

        foreach ($products as $product) {
            if (!$product->isPacked()) {
                break;
            }
        }

        $order->pack();

        $this->orderRepository->save($order);
    }
}
