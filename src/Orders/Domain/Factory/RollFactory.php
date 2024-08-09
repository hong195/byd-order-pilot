<?php

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Roll;
use App\Orders\Domain\ValueObject\Quality;

/**
 * Class RollFactory.
 *
 * This class represents a factory for creating Roll objects.
 */
class RollFactory
{
    /**
     * Creates a new Roll object.
     *
     * @param string      $name         the name of the roll
     * @param int         $length       the length of the roll
     * @param string      $quality      the quality of the roll
     * @param string      $rollType     the type of the roll
     * @param int|null    $priority     The priority of the roll. Defaults to 0 if not provided.
     * @param string|null $qualityNotes Additional notes about the quality. Defaults to null if not provided.
     *
     * @return Roll the created Roll object
     */
    public function create(string $name, int $length, string $quality, string $rollType, ?int $priority = 0, ?string $qualityNotes = null): Roll
    {
        return new Roll(
            name: $name,
        );
    }
}
