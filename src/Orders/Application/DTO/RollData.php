<?php

namespace App\Orders\Application\DTO;

/**
 *
 */
final readonly class RollData
{
    /**
     * Constructor for the class.
     *
     * @param int                $id           the ID of the object
     * @param string             $name         the name of the object
     * @param int                $length       the length of the object
     * @param string             $quality      the quality of the object
     * @param string             $rollType     the roll type of the object
     * @param \DateTimeInterface $dateAdded    the date the object was added
     * @param string|null        $qualityNotes (optional) The notes about the quality of the object
     */
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
