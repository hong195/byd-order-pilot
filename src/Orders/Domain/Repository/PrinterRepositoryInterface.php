<?php

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\Aggregate\Printer;

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
     * @return Printer[] an array of records found in the database
     */
    public function findByNames(array $names): array;
}
