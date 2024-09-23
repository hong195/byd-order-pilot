<?php

namespace App\ProductionProcess\Domain\Repository;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\ValueObject\Status;

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
     * Finds records by roll type.
     *
     * @return Roll[] an array of records matching the roll type
     */
    public function findByStatus(Status $status): array;

    /**
     * Finds records based on the given RollFilter.
     *
     * @param RollFilter $rollFilter the filter to query records
     *
     * @return Roll[] an array of matching records
     */
    public function findByFilter(RollFilter $rollFilter): array;

    /**
     * Finds a record by film id.
     *
     * @param int $filmId the film id of the record to find
     *
     * @return Roll|null the found record or null if not found
     */
    public function findByFilmId(int $filmId): ?Roll;

    /**
     * Saves a collection of rolls to the database.
     *
     * @param iterable<Roll> $rolls the collection of rolls to save
     */
    public function saveRolls(iterable $rolls): void;
}
