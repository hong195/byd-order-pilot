<?php

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Roll\Roll;
use App\Orders\Domain\Aggregate\Roll\RollType;
use App\Orders\Domain\Aggregate\ValueObject\Quality;

/**
 * Class RollFactory.
 *
 * This class represents a factory for creating Roll objects.
 */
class RollFactory
{
    /**
     * Create a new Roll object.
     *
     * @param string      $name         the name of the roll
     * @param string      $quality      the quality of the roll
     * @param string      $rollType     the type of the roll
     * @param int|null    $priority     the priority of the roll (optional)
     * @param int         $length       the length of the roll (optional)
     * @param string|null $qualityNotes any additional notes on the quality (optional)
     *
     * @return Roll the created Roll object
     */
    public function create(string $name, int $length, string $quality, string $rollType, ?int $priority = 0, ?string $qualityNotes = null): Roll
    {
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
