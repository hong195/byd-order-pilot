<?php

/**
 * FetchRollHistoryStatisticsQuery is a query class used to encapsulate the criteria
 * required to fetch the roll history statistics within the production process context.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FetchEmployerRollCountStatistics;

use App\Shared\Application\Query\Query;
use App\Shared\Domain\Repository\DateRangeFilter;

/**
 *
 */
final readonly class FetchEmployeeRollCountStatisticsQuery extends Query
{
    /**
     * @param DateRangeFilter $dateRangeFilter
     */
    public function __construct(public DateRangeFilter $dateRangeFilter)
    {
    }
}
