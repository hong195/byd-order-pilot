<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\AddRoll;

use App\Shared\Application\Command\CommandInterface;

readonly class AddRollCommand implements CommandInterface
{
    public function __construct(
        public string $name,
        public int $length,
        public string $quality,
        public string $rollType,
        public ?string $qualityNotes = null,
        public ?int $priority = null
    ) {
    }
}
