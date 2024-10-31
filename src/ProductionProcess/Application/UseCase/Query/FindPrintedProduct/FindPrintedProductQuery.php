<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindPrintedProduct;

use App\Shared\Application\Query\Query;

/**
 * Class FindPrintedProductQuery.
 *
 * Represents a query to find printed products.
 */
final readonly class FindPrintedProductQuery extends Query
{
    /**
     * Constructor for the class.
     *
     * @param int $printedProductId The printed product ID
     */
    public function __construct(public int $printedProductId)
    {
    }
}
