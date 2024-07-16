<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Command\CreateRoll;

use App\Shared\Application\Command\CommandInterface;

readonly class CreateRollCommand implements CommandInterface
{
    public function __construct(
        public string $name,
        public string $quality,
        public string $rollType,
    ) {
    }
}
