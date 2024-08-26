<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindProducts;

use App\Orders\Application\DTO\ProductData;

/**
 * Class FindProductsResult.
 *
 * Represents the result of finding products.
 */
final readonly class FindProductsResult
{
    /**
     * Class constructor.
     *
     * @param ProductData[] $products the array of products
     */
    public function __construct(public array $products)
    {
    }
}
