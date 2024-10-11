<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\GetPrintedProductsProcessDetail;

use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductProcessData;

/**
 * Represents the result of finding a roll.
 */
final readonly class GetPrintedProductsProcessDetailResult
{
    /**
     * Class constructor.
     *
     * @param PrintedProductProcessData[] $items the roll data
     */
    public function __construct(public iterable $items)
    {
    }
}
