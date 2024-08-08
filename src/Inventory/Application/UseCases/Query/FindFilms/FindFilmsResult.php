<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\FindFilms;

use App\Inventory\Application\UseCases\Query\DTO\FilmData;

/**
 * Represents the result of finding a Film.
 */
final readonly class FindFilmsResult
{
    /**
     * Class constructor.
     *
     * @param FilmData[] $items the Film data
     */
    public function __construct(public array $items)
    {
    }
}
