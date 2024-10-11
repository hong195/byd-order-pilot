<?php

/**
 * FetchRollHistoryStatisticsQueryHandler is responsible for handling the query that
 * fetches roll history statistics data by interacting with the repository to retrieve
 * and return the result.
 */

namespace App\ProductionProcess\Application\UseCase\Query\RollHistoryStatistics;

use App\ProductionProcess\Application\UseCase\Query\Repository\RollHistoryStatisticsRepositoryInterface;

/**
 *
 */
final readonly class FetchRollHistoryStatisticsQueryHandler
{
    /**
     * @param RollHistoryStatisticsRepositoryInterface $repository
     */
    public function __construct(private RollHistoryStatisticsRepositoryInterface $repository)
    {
    }

    /**
     * @param FetchRollHistoryStatisticsQuery $query
     *
     * @return RollHistoryStatisticsResult
     */
    public function __invoke(FetchRollHistoryStatisticsQuery $query): RollHistoryStatisticsResult
    {
        $data = $this->repository->findByCriteria($query->getCriteria());

        return new RollHistoryStatisticsResult($data);
    }
}
