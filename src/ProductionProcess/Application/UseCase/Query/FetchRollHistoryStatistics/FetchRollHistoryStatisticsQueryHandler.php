<?php

/**
 * FetchRollHistoryStatisticsQueryHandler is responsible for handling the query that
 * fetches roll history statistics data by interacting with the repository to retrieve
 * and return the result.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FetchRollHistoryStatistics;

use App\ProductionProcess\Application\Service\Roll\History\HistoryStatisticsListService;
use App\ProductionProcess\Domain\Repository\HistoryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 * Class FetchRollHistoryStatisticsQueryHandler.
 */
final readonly class FetchRollHistoryStatisticsQueryHandler implements QueryHandlerInterface
{
    /**
     * @param HistoryRepositoryInterface $repository
     */
    public function __construct(private HistoryRepositoryInterface $repository)
    {
    }

    /**
     * @param FetchRollHistoryStatisticsQuery $query
     *
     * @return FetchRollHistoryStatisticsResult
     */
    public function __invoke(FetchRollHistoryStatisticsQuery $query): FetchRollHistoryStatisticsResult
    {
        $result = $this->repository->findByCriteria($query->getCriteria());

        $historyStatisticsListService = new HistoryStatisticsListService($result);

        $historyDataArray = $historyStatisticsListService();

        return new FetchRollHistoryStatisticsResult($historyDataArray);
    }
}
