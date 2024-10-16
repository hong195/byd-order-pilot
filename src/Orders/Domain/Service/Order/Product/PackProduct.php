<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order\Product;

use App\Orders\Domain\Event\ProductPackedEvent;
use App\Orders\Domain\Exceptions\ProductPackException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class constructor.
 *
 * @param OrderRepositoryInterface   $orderRepository   the OrderRepositoryInterface instance
 * @param ProductRepositoryInterface $productRepository the ProductRepositoryInterface instance
 */
final readonly class PackProduct
{
    /**
     * Class constructor.
     *
     * @param OrderRepositoryInterface   $orderRepository   the OrderRepositoryInterface instance
     * @param ProductRepositoryInterface $productRepository the ProductRepositoryInterface instance
     */
    public function __construct(private OrderRepositoryInterface $orderRepository, private ProductRepositoryInterface $productRepository, private CheckProductProcessInterface $checkProductProcess, private EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * Handle logic for packing a product in an order.
     *
     * @param int $orderId   The ID of the order to pack the product in
     * @param int $productId The ID of the product to pack
     *
     * @throws NotFoundHttpException        When the order or product is not found
     * @throws ProductPackException When the product is already packed
     */
    public function handle(int $orderId, int $productId): void
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new NotFoundHttpException('Order not found');
        }

        $product = $order->getProducts()->filter(fn ($product) => $product->getId() === $productId)->first();

        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }

        if (!$this->checkProductProcess->canPack($productId)) {
            throw new ProductPackException('Product cannot be packed');
        }

        if ($product->isPacked()) {
            throw new ProductPackException('Product already packed');
        }

        $product->pack();

        $this->productRepository->save($product);

        $this->eventDispatcher->dispatch(new ProductPackedEvent(productId: $productId));
    }
}
