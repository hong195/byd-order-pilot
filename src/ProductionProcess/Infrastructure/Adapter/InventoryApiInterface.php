<?php

namespace App\ProductionProcess\Infrastructure\Adapter;

use App\Inventory\Application\UseCases\Query\DTO\FilmData;

/**
 * @interface InventoryApiInterface
 */
interface InventoryApiInterface
{
    /**
     * Gets the available films from the database.
     *
     * @return array<FilmData> an array of available films
     */
    public function getAvailableFilms(): array;

    public function getById(int $filmId): FilmData;
}
