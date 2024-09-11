<?php

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\Aggregate\Printer;
use App\Orders\Domain\ValueObject\FilmType;
use Doctrine\Common\Collections\Collection;

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
     * Find a printer by its id.
     *
     * @param int $printerId The id of the printer
     *
     * @return Printer|null The found printer or null if not found
     */
    public function findById(int $printerId): ?Printer;

    /**
     * Find all printers.
     *
     * @return Collection<Printer> An array of printers
     */
    public function all(): Collection;

    /**
     * Finds records in the database based on a given name.
     *
     * @param string[] $names the name to search by
     *
     * @return Printer[] an array of records found in the database
     */
    public function findByNames(array $names): array;

    /**
     * Finds Printer objects by the given filmType.
     *
     * @param FilmType $filmType the filmType object to search by
     *
     * @return ?Printer an array of Printer objects matching the given filmType
     */
    public function findByfilmType(FilmType $filmType): ?Printer;
}
