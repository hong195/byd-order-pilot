<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CheckInPrintedProducts;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class CheckInPrintedProductsCommandResult
{
    /**
     * Class constructor.
     *
     * @param array $unassignedPrintedProductIds the unassigned printed product IDs
     */
    public function __construct(public array $unassignedPrintedProductIds)
    {
    }
}
