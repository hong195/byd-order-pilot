<?php

declare(strict_types=1);

/**
 * Class FindEmployeeErrorsQuery.
 *
 * This class represents a query to find employee errors within a specified date range.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FindEmployerErrors;

use App\Shared\Application\Query\Query;
use App\Shared\Domain\Repository\DateRangeFilter;

/**
 * Class GetPrintedProductsProcessDetailQuery.
 *
 * This class represents a query to find rolls.
 */
final readonly class FindEmployeeErrorsQuery extends Query
{
    /**
     * @param DateRangeFilter $dateRangeFilter
     */
    public function __construct(public DateRangeFilter $dateRangeFilter)
    {
    }
}
