<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Command\UpdateFilm;

use App\Shared\Application\Command\CommandInterface;

readonly class UpdateFilmCommand implements CommandInterface
{
    /**
     * Class constructor.
     *
     * @param int    $id     the ID of the item
     * @param string $name   the name of the item
     * @param int    $length the length of the item
     */
    public function __construct(
        public int $id,
        public string $name,
        public int $length
    ) {
    }
}
