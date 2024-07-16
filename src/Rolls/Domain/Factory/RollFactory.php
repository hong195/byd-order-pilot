<?php

namespace App\Rolls\Domain\Factory;

use App\Rolls\Domain\Aggregate\Roll\Roll;
use App\Rolls\Domain\Aggregate\Roll\Quality;
use App\Rolls\Domain\Aggregate\Roll\RollType;

class RollFactory
{
    public function create(string $name, Quality $quality, RollType $rollType): Roll
    {
        return new Roll(
            name: $name,
            quality: $quality,
            rollType: $rollType
        );
    }
}
