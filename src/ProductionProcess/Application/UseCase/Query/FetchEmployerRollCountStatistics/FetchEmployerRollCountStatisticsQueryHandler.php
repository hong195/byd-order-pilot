<?php

/**
 * FetchRollHistoryStatisticsQueryHandler is responsible for handling the query that
 * fetches roll history statistics data by interacting with the repository to retrieve
 * and return the result.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FetchEmployerRollCountStatistics;

use App\ProductionProcess\Application\Service\Roll\History\EmployeeRollCountListService;
use App\ProductionProcess\Domain\Repository\Roll\RollHistory\HistoryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 * Class FetchRollHistoryStatisticsQueryHandler.
 */
final readonly class FetchEmployerRollCountStatisticsQueryHandler implements QueryHandlerInterface
{
    /**
     * @param HistoryRepositoryInterface   $repository
     * @param EmployeeRollCountListService $employeeRollCountListService
     */
    public function __construct(private HistoryRepositoryInterface $repository, private EmployeeRollCountListService $employeeRollCountListService)
    {
    }

    /**
     * @param FetchEmployeeRollCountStatisticsQuery $query
     *
     * @return FetchEmployeeRollCountStatisticsResult
     */
    public function __invoke(FetchEmployeeRollCountStatisticsQuery $query): FetchEmployeeRollCountStatisticsResult
    {
        $result = $this->repository->findEmployeeProcessCounts($query->dateRangeFilter);

        $employeeService = $this->employeeRollCountListService;

        $data = $employeeService($result);

        return new FetchEmployeeRollCountStatisticsResult($data);
    }
}
