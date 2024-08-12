<?php

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\Aggregate\Printer;
use App\Orders\Domain\ValueObject\RollType;

/**
 * Interface for interacting with the Printer repository.
 */
interface PrinterRepositoryInterface
{
    /**
     * Saves the given Printer object to the database.
     *
     * @param Printer $printer the Printer object to be saved
     */
    public function save(Printer $printer): void;

    /**
     * Finds records in the database based on a given name.
     *
     * @param string[] $names the name to search by
     *
     * @return Printer[] an array of records found in the database
     */
    public function findByNames(array $names): array;

    /**
     * Finds Printer objects by the given RollType.
     *
     * @param RollType $rollType the RollType object to search by
     *
     * @return ?Printer an array of Printer objects matching the given RollType
     */
    public function findByRollType(RollType $rollType): ?Printer;
}
