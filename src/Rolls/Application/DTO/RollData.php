<?php

namespace App\Rolls\Application\DTO;

final readonly class RollData
{
    public function __construct(
        public int $id,
        public string $name,
        public int $length,
        public string $quality,
        public string $rollType,
        public \DateTimeInterface $dateAdded,
        public ?string $qualityNotes = null,
    ) {
    }
}
