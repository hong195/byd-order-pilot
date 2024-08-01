<?php

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\Aggregate\Roll\Roll;

/**
 * This interface represents a Roll repository.
 */
interface RollRepositoryInterface
{
    /**
     * Adds a new Roll record.
     *
     * @param Roll $roll the Roll record to add
     */
    public function add(Roll $roll): void;

    /**
     * Finds a record by id.
     *
     * @param int $id the id of the record to find
     *
     * @return Roll|null the found record or null if not found
     */
    public function findById(int $id): ?Roll;

    /**
     * Saves a Roll object to the database.
     *
     * @param Roll $roll the Roll object to be saved
     */
    public function save(Roll $roll): void;

    /**
     * Remove the specified roll from the database.
     *
     * @param Roll $roll the roll object to be removed
     */
    public function remove(Roll $roll): void;

    /**
     * Finds records based on the given RollFilter.
     *
     * @param RollFilter $rollFilter the filter to query records
     *
     * @return Roll[] an array of matching records
     */
    public function findQueried(RollFilter $rollFilter): array;
}
