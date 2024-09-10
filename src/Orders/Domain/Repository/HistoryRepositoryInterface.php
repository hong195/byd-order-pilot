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
    public function save(History $history): void;

    /**
     * Finds a history record by its roll ID.
     *
     * @param int $rollId the roll ID of the history record to be found
     *
     * @return History[] the found history record or null if no record is found
     */
    public function findByRollId(int $rollId): array;

    /**
     * Finds an unfinished history record by roll ID.
     *
     * @param int $rollId the roll ID to search for
     *
     * @return History|null the unfinished history record, or null if not found
     */
    public function findUnfinished(int $rollId): ?History;

    /**
     * Saves multiple history records.
     *
     * @param iterable $histories a collection of History objects to be saved
     */
    public function saveMany(iterable $histories): void;

    /**
     * Deletes a History record.
     *
     * @param History[] $histories the History record to be deleted
     */
    public function deleteAll(iterable $histories): void;
}
