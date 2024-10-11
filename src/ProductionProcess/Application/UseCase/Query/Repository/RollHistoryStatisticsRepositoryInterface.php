<?php

/**
 * RollHistoryStatisticsRepositoryInterface provides an abstraction for querying roll history statistics.
 */

namespace App\ProductionProcess\Application\UseCase\Query\Repository;

use App\ProductionProcess\Application\UseCase\Query\RollHistoryStatistics\RollHistoryStatisticsFilterCriteria;
use App\ProductionProcess\Domain\DTO\RollHistoryStatistics;

/**
 * Interface RollHistoryStatisticsRepositoryInterface.
 *
 * Defines a repository method for fetching roll history statistics based on specific filter criteria.
 */
interface RollHistoryStatisticsRepositoryInterface
{
    /**
     * @param RollHistoryStatisticsFilterCriteria $criteria
     *
     * @return RollHistoryStatistics[]
     */
    public function findByCriteria(RollHistoryStatisticsFilterCriteria $criteria): array;
}
