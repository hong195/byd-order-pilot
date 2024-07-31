<?php

namespace App\Orders\Application\DTO;

final readonly class LaminationData
{
    public function __construct(
        public int $id,
        public string $name,
        public int $length,
        public string $quality,
        public string $laminationType,
        public \DateTimeInterface $dateAdded,
        public ?string $qualityNotes = null,
    ) {
    }
}
