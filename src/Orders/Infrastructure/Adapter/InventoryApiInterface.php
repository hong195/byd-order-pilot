<?php

namespace App\Orders\Infrastructure\Adapter;

use App\Orders\Domain\DTO\FilmData;

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
