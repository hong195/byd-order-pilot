<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

use App\Orders\Domain\Aggregate\Product;

/**
 * ProductData class represents product data.
 */
final readonly class ProductDataTransformer
{
    /**
     * Converts an array of ProductData objects to an array of ProductData objects with modified properties.
     *
     * @param Product[] $products an array of ProductData objects
     *
     * @return ProductData[] an array of ProductData objects with modified properties
     */
    public function fromProductsList(array $products): array
    {
        return array_map(
            fn (Product $product) => new ProductData(
                $product->getId(),
                $product->filmType->value,
                $product->laminationType?->value
            ),
            $products
        );
    }
}
