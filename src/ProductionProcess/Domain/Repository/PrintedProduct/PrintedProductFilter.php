<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Repository\PrintedProduct;

/**
 * Class PrintedProductsFilter.
 *
 * This class represents a filter for printed products.
 */
final class PrintedProductFilter
{
    /**
     * Class constructor.
     */
    public function __construct(public ?bool $unassigned = null, public ?int $rollId = null, public array $ids = [])
    {
    }
}
