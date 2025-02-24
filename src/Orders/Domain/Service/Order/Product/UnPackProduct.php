<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order\Product;

use App\Orders\Domain\Exceptions\ProductUnPackException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class UnPackProduct
{
    /**
     * Constructor for initializing OrderService.
     */
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * Handle the un-packing process for a product in an order.
     *
     * @param string $productId The ID of the product to be un-packed
     *
     * @throws NotFoundHttpException  If the order or product is not found
     * @throws ProductUnPackException
     */
    public function handle(string $productId): void
    {
        $product = $this->productRepository->findById($productId);

        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }

        if (!$product->isPacked()) {
            throw new ProductUnPackException('Product already un-packed');
        }

        $product->unpack();

        $this->productRepository->save($product);
	}
}
