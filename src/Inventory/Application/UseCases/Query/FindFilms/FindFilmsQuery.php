<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\FindFilms;

use App\Shared\Application\Query\Query;

/**
 * Class FindFilmsQuery.
 *
 * This class represents a query to find Films.
 */
final readonly class FindFilmsQuery extends Query
{
    /**
     * Class Constructor.
     *
     * @param string|null $filmType The type of Film
     */
    public function __construct(public ?string $filmType)
    {
    }
}
