<?php

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Lamination\Lamination;
use App\Orders\Domain\Aggregate\Lamination\LaminationType;
use App\Orders\Domain\Aggregate\Quality;

class LaminationFactory
{
    public function create(
        string $name,
        string $quality,
        string $laminationType,
        int $priority = 0,
        int $length = 0,
        ?string $qualityNotes = null,
    ): Lamination {
        return new Lamination(
            name: $name,
            quality: Quality::from($quality),
            laminationType: LaminationType::from($laminationType),
            length: $length,
            qualityNotes: $qualityNotes,
            priority: $priority
        );
    }
}
