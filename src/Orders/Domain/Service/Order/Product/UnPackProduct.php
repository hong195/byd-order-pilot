<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order\Product;

use App\Orders\Domain\Exceptions\CantPackMainProductException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class UnPackProduct
{
    /**
     * Constructor for initializing OrderService.
     */
    public function __construct(private OrderRepositoryInterface $orderRepository, private ProductRepositoryInterface $productRepository, private CheckProductProcessInterface $checkProductProcess)
    {
    }

    /**
     * Handle the un-packing process for a product in an order.
     *
     * @param int $orderId   The ID of the order
     * @param int $productId The ID of the product to be un-packed
     *
     * @throws NotFoundHttpException        If the order or product is not found
     * @throws CantPackMainProductException If unpacking conditions are not met
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
            throw new CantPackMainProductException('Product cannot be un-packed');
        }

        if (!$product->isPacked()) {
            throw new CantPackMainProductException('Product already un-packed');
        }

        $product->unpack();

        $this->productRepository->save($product);
    }
}
