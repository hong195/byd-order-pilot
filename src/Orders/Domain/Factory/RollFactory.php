<?php

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Quality;
use App\Orders\Domain\Aggregate\Roll\Roll;
use App\Orders\Domain\Aggregate\Roll\RollType;

class RollFactory
{
    public function create(
        string $name,
        string $quality,
        string $rollType,
        int $priority = 0,
        int $length = 0,
        ?string $qualityNotes = null,
    ): Roll {
        return new Roll(
            name: $name,
            quality: Quality::from($quality),
            rollType: RollType::from($rollType),
            length: $length,
            qualityNotes: $qualityNotes,
            priority: $priority
        );
    }
}
