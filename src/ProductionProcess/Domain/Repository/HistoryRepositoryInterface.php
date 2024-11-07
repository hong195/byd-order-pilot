<?php

/**
 * Interface HistoryRepositoryInterface.
 */

namespace App\ProductionProcess\Domain\Repository;

use App\ProductionProcess\Domain\Aggregate\Roll\History\History;

/**
 * Interface HistoryRepositoryInterface.
 */
interface HistoryRepositoryInterface
{
    /**
     * Saves a history record.
     * @param History $history
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
     * @param History[] $filter
     *
     * @return History[]
     */
    public function findByCriteria(FetchRollHistoryStatisticsFilter $filter): array;
}
