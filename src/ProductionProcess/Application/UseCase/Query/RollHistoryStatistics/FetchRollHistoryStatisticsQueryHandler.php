<?php

/**
 * FetchRollHistoryStatisticsQueryHandler is responsible for handling the query that
 * fetches roll history statistics data by interacting with the repository to retrieve
 * and return the result.
 */

namespace App\ProductionProcess\Application\UseCase\Query\RollHistoryStatistics;

use App\ProductionProcess\Domain\Aggregate\Roll\History\History;
use App\ProductionProcess\Domain\Repository\RollHistoryStatisticsRepositoryInterface;

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
     * @return History[]
     */
    public function __invoke(FetchRollHistoryStatisticsQuery $query): array
    {
        return $this->repository->findByCriteria($query->getCriteria());
    }
}
