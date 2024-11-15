<?php

/**
 * FetchRollHistoryStatisticsQueryHandler is responsible for handling the query that
 * fetches roll history statistics data by interacting with the repository to retrieve
 * and return the result.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FetchEmployerRollCountStatistics;

use App\ProductionProcess\Application\Service\Roll\History\EmployerRollCountListService;
use App\ProductionProcess\Domain\Repository\Statistics\RollHistory\HistoryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 * Class FetchRollHistoryStatisticsQueryHandler.
 */
final readonly class FetchEmployerRollCountStatisticsQueryHandler implements QueryHandlerInterface
{
    /**
     * @param HistoryRepositoryInterface   $repository
     * @param EmployerRollCountListService $employerRollCountListService
     */
    public function __construct(private HistoryRepositoryInterface $repository, private EmployerRollCountListService $employerRollCountListService)
    {
    }

    /**
     * @param FetchEmployerRollCountStatisticsQuery $query
     *
     * @return FetchEmployerRollCountStatisticsResult
     */
    public function __invoke(FetchEmployerRollCountStatisticsQuery $query): FetchEmployerRollCountStatisticsResult
    {
        $result = $this->repository->findByDateRangeForEmployers($query->dateRangeFilter);

        $employerService = $this->employerRollCountListService;

        $data = $employerService($result);

        return new FetchEmployerRollCountStatisticsResult($data);
    }
}
