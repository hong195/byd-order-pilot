<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Command\UpdateFilm;

use App\Shared\Application\Command\CommandInterface;

readonly class UpdateFilmCommand implements CommandInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param int         $id       the ID of the object
     * @param string      $filmType the type of the film
     * @param string|null $name     the name of the object (optional)
     * @param float|null    $length   the length of the film (optional)
     * @param string|null $type     the type of the object (optional)
     */
    public function __construct(public int $id, public string $filmType, public ?string $name = null, public ?float $length = null, public ?string $type = null)
    {
    }
}
