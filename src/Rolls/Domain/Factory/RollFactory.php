<?php

namespace App\Rolls\Domain\Factory;

use App\Rolls\Domain\Aggregate\Roll\Quality;
use App\Rolls\Domain\Aggregate\Roll\Roll;
use App\Rolls\Domain\Aggregate\Roll\RollType;

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
