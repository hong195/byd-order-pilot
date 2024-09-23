<?php

namespace App\ProductionProcess\Infrastructure\Adapter;

use App\ProductionProcess\Domain\DTO\FilmData;

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
}
