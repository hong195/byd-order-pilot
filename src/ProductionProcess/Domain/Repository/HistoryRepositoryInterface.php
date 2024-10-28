<?php

namespace App\ProductionProcess\Domain\Repository;

use App\ProductionProcess\Application\UseCase\Query\FetchRollHistoryStatistics\RollHistoryStatisticsFilterCriteria;
use App\ProductionProcess\Domain\Aggregate\Roll\History\History;

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
    public function findByRollId(int $rollId): array;

    /**
     * @param RollHistoryStatisticsFilterCriteria $criteria
     *
     * @return History[]
     */
    public function findByCriteria(RollHistoryStatisticsFilterCriteria $criteria): array;
}
