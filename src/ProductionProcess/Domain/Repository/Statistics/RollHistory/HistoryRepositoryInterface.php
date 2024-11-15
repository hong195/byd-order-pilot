<?php

/**
 * Interface HistoryRepositoryInterface.
 */

namespace App\ProductionProcess\Domain\Repository\Statistics\RollHistory;

use App\ProductionProcess\Application\DTO\EmployerRollCountData;
use App\ProductionProcess\Domain\Aggregate\Roll\History\History;
use App\Shared\Domain\Repository\DateRangeFilter;

/**
 * Interface HistoryRepositoryInterface.
 */
interface HistoryRepositoryInterface
{
    /**
     * Saves a history record.
     */
    public function add(History $history): void;

    /**
     * Finds a history record by its roll ID.
     *
     * @param int $rollId the roll ID of the history record to be found
     *
     * @return History[] the found history record or null if no record is found
     */
    public function findFullHistory(int $rollId): array;

    /**
     * @return History[]
     */
    public function findByFilter(FetchRollHistoryStatisticsFilter $filter): array;

    /**
     * @param DateRangeFilter $filter
     *
     * @return EmployerRollCountData[]
     */
    public function findByDateRangeForEmployers(DateRangeFilter $filter): array;
}
