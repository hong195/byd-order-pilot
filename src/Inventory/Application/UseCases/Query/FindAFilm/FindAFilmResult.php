<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\FindAFilm;

use App\Inventory\Application\UseCases\Query\DTO\FilmData;

/**
 * Represents the result of finding a Film.
 */
final readonly class FindAFilmResult
{
    /**
     * @param FilmData $FilmData the Film data object
     *
     * @return void
     */
    public function __construct(public FilmData $FilmData)
    {
    }
}
