<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Command\AddRoll;

use App\Shared\Application\Command\CommandInterface;

readonly class AddRollCommand implements CommandInterface
{
    public function __construct(
        public string $name,
        public string $quality,
        public string $rollType,
    ) {
    }
}
