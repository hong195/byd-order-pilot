<?php

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\Aggregate\Roll\History;

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
     * Saves multiple history records.
     *
     * @param iterable<int> $histories a collection of History objects to be saved
     */
    public function saveMany(iterable $histories): void;

    /**
     * Deletes a History record.
     */
    public function delete(History $history): void;
}
