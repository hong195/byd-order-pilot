<?php

declare(strict_types=1);

/**
 * Class FindEmployerErrorsQuery.
 *
 * This class represents a query to find employer errors within a specified date range.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FindEmployerErrors;

use App\Shared\Application\Query\Query;
use App\Shared\Domain\Repository\DateRangeFilter;

/**
 * Class GetPrintedProductsProcessDetailQuery.
 *
 * This class represents a query to find rolls.
 */
final readonly class FindEmployerErrorsQuery extends Query
{
    /**
     * @param DateRangeFilter $filter
     */
    public function __construct(public DateRangeFilter $filter)
    {
    }

    /**
     * Get the error filter instance based on responsible employee id, noticer id, and process.
     *
     * @return DateRangeFilter the error filter instance created with the provided parameters
     */
    public function getErrorFilter(): DateRangeFilter
    {
        return $this->filter;
    }
}
