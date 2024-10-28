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
     * @param RollHistoryStatisticsFilterCriteria $criteria
     */
    public function __construct(public RollHistoryStatisticsFilterCriteria $criteria)
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
