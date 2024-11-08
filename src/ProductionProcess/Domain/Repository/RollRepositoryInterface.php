<?php

namespace App\ProductionProcess\Domain\Repository;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use Doctrine\Common\Collections\Collection;

/**
 * This interface represents a Roll repository.
 */
interface RollRepositoryInterface
{
    /**
     * Finds a record by id.
     *
     * @param int $id the id of the record to find
     *
     * @return Roll|null the found record or null if not found
     */
    public function findById(int $id): ?Roll;

    /**
     * Finds a record by film id.
     *
     * @param int $filmId the film id of the record to find
     *
     * @return Collection<Roll> the found record or null if not found
     */
    public function findByFilmId(int $filmId): Collection;

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
    public function findByFilter(RollFilter $rollFilter): array;
}
