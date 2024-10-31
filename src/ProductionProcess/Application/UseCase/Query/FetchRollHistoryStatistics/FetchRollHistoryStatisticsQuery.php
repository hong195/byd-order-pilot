<?php

/**
 * FetchRollHistoryStatisticsQuery is a query class used to encapsulate the criteria
 * required to fetch the roll history statistics within the production process context.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FetchRollHistoryStatistics;

use App\ProductionProcess\Domain\Repository\FetchRollHistoryStatisticsFilter;
use App\Shared\Application\Query\Query;

/**
 *
 */
final readonly class FetchRollHistoryStatisticsQuery extends Query
{
    /**
     * @param FetchRollHistoryStatisticsFilter $criteria
     */
    public function __construct(public FetchRollHistoryStatisticsFilter $criteria)
    {
    }

    /**
     * @return FetchRollHistoryStatisticsFilter
     */
    public function getCriteria(): FetchRollHistoryStatisticsFilter
    {
        return $this->criteria;
    }
}
