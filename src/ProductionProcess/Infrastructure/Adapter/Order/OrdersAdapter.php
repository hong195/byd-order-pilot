<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Adapter\Order;

use App\ProductionProcess\Application\Service\PrintedProduct\RelatedProductsInterface;

final readonly class OrdersAdapter implements RelatedProductsInterface
{
    public function __construct(private OrderApiInterface $adapter)
    {
    }

    /**
     * Finds a product by its ID.
     *
     * @param int $productId the ID of the product to find
     *
     * @return mixed the found product or null if not found
     */
    public function findProductById(int $productId): mixed
    {
        return $this->adapter->findProductById($productId);
    }

    /**
     * Finds products by their IDs.
     *
     * @param array $relatedProductsIds An array of product IDs to find
     *
     * @return mixed An array of found products indexed by their IDs
     */
    public function findProductsByIds(array $relatedProductsIds): array
    {
        return $this->adapter->findProductByIds($relatedProductsIds);
    }
}
