<?php

/**
 * FetchRollHistoryStatisticsQuery is a query class used to encapsulate the criteria
 * required to fetch the roll history statistics within the production process context.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FetchRollHistoryStatistics;

use App\Shared\Application\Query\Query;

/**
 *
 */
final readonly class FetchRollHistoryStatisticsQuery extends Query
{
    /**
     * @param RollHistoryStatisticsFilter $criteria
     */
    public function __construct(public RollHistoryStatisticsFilter $criteria)
    {
    }

    /**
     * @return RollHistoryStatisticsFilter
     */
    public function getCriteria(): RollHistoryStatisticsFilter
    {
        return $this->criteria;
    }
}
