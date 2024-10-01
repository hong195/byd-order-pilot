<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindPrintedProducts;

use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FindPrintedProductsQueryResult
{
    /**
     * Class constructor.
     *
     * @param <int,PrintedProductData[]> $items the roll data
     */
    public function __construct(public array $items)
    {
    }
}
