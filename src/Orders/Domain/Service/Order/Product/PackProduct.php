<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order\Product;

use App\Orders\Domain\Aggregate\Product;
use App\Orders\Domain\Exceptions\ProductCheckException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use App\Orders\Domain\ValueObject\Process;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * A final readonly class responsible for creating products.
 */
final readonly class PackProduct
{
    /**
     * Class constructor.
     *
     * @param OrderRepositoryInterface   $orderRepository   the order repository
     * @param ProductRepositoryInterface $productRepository the product repository
     */
    public function __construct(private OrderRepositoryInterface $orderRepository, private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * Handles checking/unchecking a product in an order.
     *
     * @param int  $orderId   the ID of the order
     * @param int  $productId the ID of the product
     * @param bool $isPacked  the flag indicating whether the product is checked or not
     *
     * @throws NotFoundHttpException if the order or product is not found
     * @throws ProductCheckException if the roll is not found or in the cutting check in process
     */
    public function handle(int $orderId, int $productId, bool $isPacked): void
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new NotFoundHttpException('Order not found');
        }

        $roll = $order->getRoll();

        if (!$roll || $roll->getProcess()->equals(Process::CUTTING_CHECK_IN)) {
            // throw new ProductCheckException('Roll is not in cut check in process!');
        }

        /** @var Product $product */
        $product = $order->getProducts()->filter(fn (Product $product) => $product->getId() === $productId)->first();

        if (!$product) {
            throw new NotFoundHttpException('Product not found!');
        }

        $product->setIsPacked($isPacked);
        $this->productRepository->save($product);
    }
}
