<?php

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\Aggregate\Product;

/**
 * Interface ProductRepositoryInterface.
 *
 * Represents a product repository.
 */
interface ProductRepositoryInterface
{
    /**
     * Adds a product to the application.
     *
     * @param Product $product the product to be added
     */
    public function add(Product $product);
}
