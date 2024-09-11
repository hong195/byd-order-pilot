<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindRollHistory;

use App\Orders\Application\DTO\EmployeeData;
use App\Orders\Application\DTO\HistoryData;
use App\Orders\Application\DTO\RollDataTransformer;
use App\Orders\Application\Service\Employee\EmployeeFetcher;
use App\Orders\Domain\Aggregate\Roll\History;
use App\Orders\Domain\Repository\HistoryRepositoryInterface;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * FindRollHistoryHandler constructor.
 *
 * @param RollRepositoryInterface $rollRepository
 * @param RollDataTransformer     $rollDataTransformer
 */
final readonly class FindRollHistoryHandler implements QueryHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService       $accessControlService the access control service instance
     * @param HistoryRepositoryInterface $historyRepository    the history repository instance
     * @param HistoryData                $historyData          the history data instance
     */
    public function __construct(private AccessControlService $accessControlService, private HistoryRepositoryInterface $historyRepository, private HistoryData $historyData, private EmployeeFetcher $employeeFetcher)
    {
    }

    /**
     * Executes the FindRollHistoryQuery and returns the result.
     *
     * @param FindRollHistoryQuery $rollQuery the query object used to find rolls
     *
     * @return FindRollHistoryResult the result object containing the rolls data
     */
    public function __invoke(FindRollHistoryQuery $rollQuery): FindRollHistoryResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $histories = $this->historyRepository->findByRollId($rollQuery->rollId);
        $employees = $this->employeeFetcher->getByIds(array_map(fn ($history) => $history->getEmployeeId(), $histories));

        $historyData = [];
        foreach ($histories as $history) {
            $data = $this->historyData->fromHistory($history);
            $employee = $employees->filter(fn (EmployeeData $employee) => $employee->id === $history->getEmployeeId())->first();

            $data->withEmployee($employee ?: null);

            $historyData[] = $data;
        }

        return new FindRollHistoryResult($historyData);
    }
}
