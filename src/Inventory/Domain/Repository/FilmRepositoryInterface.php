<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Repository;

use App\Inventory\Domain\Aggregate\AbstractFilm;

/**
 * Interface FilmRepositoryInterface.
 *
 * Defines methods for interacting with the film database.
 */
interface FilmRepositoryInterface
{
    /**
     * Saves a film into the database.
     *
     * @param AbstractFilm $film the film to save
     */
    public function save(AbstractFilm $film): void;

    /**
     * Remove the given film from the database.
     *
     * @param AbstractFilm $film the film to be removed
     */
    public function remove(AbstractFilm $film): void;

    /**
     * Find all available films.
     *
     * @return AbstractFilm[] an array of available film entities
     */
    public function findAvailable(): array;

    /**
     * Finds a film by its id.
     *
     * @param string $id the id of the film to find
     *
     * @return AbstractFilm|null the found film or null if not found
     */
    public function findById(string $id): ?AbstractFilm;

    /**
     * Finds all films in the database.
     *
     * @return AbstractFilm[] an array of AbstractFilm objects representing all films in the database
     */
    public function findByFilter(FilmFilter $filmFilter): array;
}
