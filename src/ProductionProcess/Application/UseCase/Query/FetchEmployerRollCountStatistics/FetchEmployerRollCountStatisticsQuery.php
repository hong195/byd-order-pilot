<?php

/**
 * FetchRollHistoryStatisticsQuery is a query class used to encapsulate the criteria
 * required to fetch the roll history statistics within the production process context.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FetchEmployerRollCountStatistics;

use App\ProductionProcess\Domain\Repository\DateRangeFilter;
use App\Shared\Application\Query\Query;

/**
 *
 */
final readonly class FetchEmployerRollCountStatisticsQuery extends Query
{
    /**
     * @param DateRangeFilter $filter
     */
    public function __construct(public DateRangeFilter $filter)
    {
    }

    /**
     * @return DateRangeFilter
     */
    public function getCriteria(): DateRangeFilter
    {
        return $this->filter;
    }
}
