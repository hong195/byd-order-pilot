<?php

declare(strict_types=1);

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Product;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;

final readonly class ProductFactory
{
    /**
     * Create a new Product instance.
     *
     * @param FilmType            $filmType       the film type for the product
     * @param int                 $length         the length of the product
     * @param LaminationType|null $laminationType the lamination type for the product (optional)
     *
     * @return Product the created Product instance
     */
    public function make(FilmType $filmType, int $length, ?LaminationType $laminationType = null): Product
    {
        $product = new Product($filmType, $length);

        if ($laminationType) {
            $product->setLaminationType($laminationType);
        }

        return $product;
    }
}
