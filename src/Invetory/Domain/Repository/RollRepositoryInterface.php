<?php

namespace App\Invetory\Domain\Repository;

use App\Invetory\Domain\Aggregate\Roll;

/**
 * Roll interface for handling roll operations.
 */
interface RollRepositoryInterface
{
    /**
     * Finds a Roll by its id.
     *
     * @param int $id the id of the Roll to find
     *
     * @return Roll|null the Roll if found, null otherwise
     */
    public function findById(int $id): ?Roll;

    /**
     * Saves a Roll.
     *
     * @param Roll $roll the Roll to save
     */
    public function save(Roll $roll): void;

    /**
     * Removes a Roll from the database.
     *
     * @param Roll $roll the Roll to remove
     */
    public function remove(Roll $roll): void;

    /**
     * Finds Rolls by their type.
     *
     * @param string $type the type of the Rolls to find
     *
     * @return Roll[] an array of Rolls matching the given type
     */
    public function finByType(string $type): array;
}
