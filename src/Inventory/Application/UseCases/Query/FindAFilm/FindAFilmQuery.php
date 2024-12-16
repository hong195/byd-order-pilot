<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\FindAFilm;

use App\Shared\Application\Query\Query;

/**
 * Class FindAFilmQuery.
 *
 * This class represents a query to find a Film by its ID.
 */
final readonly class FindAFilmQuery extends Query
{
    /**
     * Class constructor.
     *
     * @param string $filmId the Film ID
     */
    public function __construct(public string $filmId)
    {
    }
}
