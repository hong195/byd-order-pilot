<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Command\DeleteFilm;

use App\Shared\Application\Command\CommandInterface;

/**
 * Class DeleteFilmCommand.
 */
readonly class DeleteFilmCommand implements CommandInterface
{
    public function __construct(
        public int $id
    ) {
    }
}
