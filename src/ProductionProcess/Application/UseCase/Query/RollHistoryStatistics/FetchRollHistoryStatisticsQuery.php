<?php

/**
 * FetchRollHistoryStatisticsQuery is a query class used to encapsulate the criteria
 * required to fetch the roll history statistics within the production process context.
 */

namespace App\ProductionProcess\Application\UseCase\Query\RollHistoryStatistics;

/**
 *
 */
final readonly class FetchRollHistoryStatisticsQuery
{
    /**
     * @param RollHistoryStatisticsFilterCriteria $criteria
     */
    public function __construct(private RollHistoryStatisticsFilterCriteria $criteria)
    {
    }

    /**
     * @return RollHistoryStatisticsFilterCriteria
     */
    public function getCriteria(): RollHistoryStatisticsFilterCriteria
    {
        return $this->criteria;
    }
}
