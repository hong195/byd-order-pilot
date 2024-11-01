<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\Service\Roll\History;

use App\ProductionProcess\Application\DTO\EmployeeData;
use App\ProductionProcess\Application\DTO\HistoryData;
use App\ProductionProcess\Application\Service\Employee\EmployeeFetcher;
use App\ProductionProcess\Domain\Repository\HistoryRepositoryInterface;

/**
 * Class HistoryListService.
 */
final readonly class HistoryListService
{
    /**
     * Class MyClass.
     */
    public function __construct(private HistoryRepositoryInterface $historyRepository, private HistoryData $historyData, private EmployeeFetcher $employeeFetcher)
    {
    }

    /**
     * Retrieves the history data for a given roll ID.
     *
     * @param int $rollId the ID of the roll to retrieve history for
     *
     * @return HistoryData[] an array of HistoryData objects
     */
    public function __invoke(int $rollId): array
    {
        $histories = $this->historyRepository->findFullHistory($rollId);
		$historyIds = array_unique(array_filter(array_map(fn ($history) => $history->getEmployeeId(), $histories)));
        $employees = $this->employeeFetcher->getByIds($historyIds);

        $historyData = [];
        foreach ($histories as $history) {
            $data = $this->historyData->fromHistory($history);
            $employee = $employees->filter(fn (EmployeeData $employee) => $employee->id === $history->getEmployeeId())->first();

            $data->withEmployee($employee ?: null);

            $historyData[] = $data;
        }

        return $historyData;
    }
}
