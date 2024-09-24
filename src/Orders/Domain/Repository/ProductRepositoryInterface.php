<?php

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\Aggregate\Extra;
use App\Orders\Domain\Aggregate\Product;

/**
 * Interface ExtraRepositoryInterface.
 *
 * Represents a repository for storing Extra objects.
 */
interface ProductRepositoryInterface
{
    /**
     * Adds a product to the application.
     *
     * @param Product $product The product to be added
     */
    public function add(Product $product): void;

    /**
     * Finds records by order ID.
     *
     * @param int $orderId the order ID
     *
     * @return array the records matching the order ID
     */
    public function findByOrderId(int $orderId): array;
}
