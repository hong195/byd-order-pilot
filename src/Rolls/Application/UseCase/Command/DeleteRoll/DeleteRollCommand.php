<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Command\DeleteRoll;

use App\Shared\Application\Command\CommandInterface;

readonly class DeleteRollCommand implements CommandInterface
{
    public function __construct(
        public int $id
    ) {
    }
}
