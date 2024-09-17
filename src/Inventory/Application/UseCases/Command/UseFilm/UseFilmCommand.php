<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Command\UseFilm;

use App\Shared\Application\Command\CommandInterface;

readonly class UseFilmCommand implements CommandInterface
{
    /**
     * Class constructor.
     *
     * @param int   $filmId     the ID of the object
     * @param float $length the length of the object
     */
    public function __construct(public int $filmId, public float $length)
    {
    }
}
