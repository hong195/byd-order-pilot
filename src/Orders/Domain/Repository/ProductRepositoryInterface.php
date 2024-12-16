<?php

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\Aggregate\Product;
use Doctrine\Common\Collections\Collection;

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
     * @param string $productId The product ID
     *
     * @return ?Product The product matching the ID
     */
    public function findById(string $productId): ?Product;

    /**
     * Finds products based on a filter.
     *
     * @param ProductFilter $filter The filter object containing criteria for product search
     */
    public function findByFilter(ProductFilter $filter): Collection;
}
