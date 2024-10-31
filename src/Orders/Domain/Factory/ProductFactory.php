<?php

declare(strict_types=1);

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Product;

final readonly class ProductFactory
{
    /**
     * Creates a new Product object with the given film type and length.
     *
     * @param string      $filmType       The type of film for the product
     * @param float       $length         The length of the product
     * @param string|null $laminationType The type of lamination for the product (optional)
     *
     * @return Product The newly created product object
     */
    public function make(string $filmType, float $length, ?string $laminationType = null): Product
    {
        $product = new Product($filmType, $length);

        if ($laminationType) {
            $product->setLaminationType($laminationType);
        }

        return $product;
    }
}
