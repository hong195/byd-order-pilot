<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UpdateRoll;

use App\Shared\Application\Command\CommandInterface;

readonly class UpdateRollCommand implements CommandInterface
{
    public function __construct(
        public int $id,
        public string $name,
        public string $quality,
        public string $rollType,
        public int $length,
        public int $priority = 0,
        public ?string $qualityNotes = null
    ) {
    }
}
