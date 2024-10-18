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
    public function save(Product $product): void;
    /**
     * Finds a product by its ID.
     *
     * @param int $productId The product ID
     *
     * @return ?Product The product matching the ID
     */
    public function findById(int $productId): ?Product;
}
