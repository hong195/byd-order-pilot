<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindPrintedProduct;

use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FindPrintedProductQueryResult
{
    public function __construct(public ?PrintedProductData $printedProduct = null)
    {
    }
}
