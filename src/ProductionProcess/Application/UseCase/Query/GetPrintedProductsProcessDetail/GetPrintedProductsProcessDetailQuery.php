<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\GetPrintedProductsProcessDetail;

use App\Shared\Application\Query\Query;

/**
 * Class GetPrintedProductsProcessDetailQuery.
 *
 * This class represents a query to find rolls.
 */
final readonly class GetPrintedProductsProcessDetailQuery extends Query
{
    /**
     * @param iterable<int> $relatedProductIds
     */
    public function __construct(public iterable $relatedProductIds)
    {
    }
}
