<?php

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Roll;
use App\Orders\Domain\ValueObject\Process;

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
     * @param string   $name   the name of the Roll
     * @param int|null $filmId The ID of the associated Roll Film. Optional, defaults to null.
     *
     * @return Roll the created Roll object
     */
    public function create(string $name, ?int $filmId = null, ?Process $process = null): Roll
    {
        return new Roll(name: $name, filmId: $filmId, process: $process);
    }
}
