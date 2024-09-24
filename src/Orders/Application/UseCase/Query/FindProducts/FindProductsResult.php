<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindProducts;

use App\Orders\Application\DTO\Order\OrderData;
use App\Orders\Application\DTO\Product\ProductData;

/**
 * Represents the result of finding an order.
 */
final readonly class FindProductsResult
{
    /**
     * Class MyClass.
     *
     * This class represents a MyClass object.
     *
     * @param array<ProductData[]> $items the items to include in the result
     */
    public function __construct(public array $items = [], public int $total = 0)
    {
    }
}
