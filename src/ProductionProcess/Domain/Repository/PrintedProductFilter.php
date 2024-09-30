<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Repository;

/**
 * Class PrintedProductsFilter.
 *
 * This class represents a filter for printed products.
 */
final class PrintedProductFilter
{
    /**
     * Class constructor.
     *
     * @param bool|null $unassigned Boolean indicating if the item is unassigned. Default is null.
     * @param int|null  $rollId     The ID of the roll. Default is null.
     */
    public function __construct(public ?bool $unassigned = null, public ?int $rollId = null)
    {
    }
}
