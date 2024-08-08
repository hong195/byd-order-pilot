<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Command\AddFilm;

use App\Shared\Application\Command\CommandInterface;

/**
 * Class AddFilmCommand
 * Implements CommandInterface.
 *
 * Represents a command to add a Film.
 */
readonly class AddFilmCommand implements CommandInterface
{
    /**
     * @param string $name     the name of the object
     * @param int    $length   the length of the object
     * @param string $filmType the FilmType of the object
     */
    public function __construct(public string $name, public int $length, public string $filmType, public string $type)
    {
    }
}
