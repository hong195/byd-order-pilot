<?php

namespace App\ProductionProcess\Domain\Factory;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\ValueObject\Process;

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
     * @param string   $name    the name of the roll
     * @param ?int     $filmId  The ID of the film associated with the roll. Can be null.
     * @param ?Process $process The process associated with the roll. Can be null.
     *
     * @return Roll the newly created Roll object
     */
    public function create(string $name, ?int $filmId = null, ?Process $process = null): Roll
    {
        return new Roll(name: $name, filmId: $filmId, process: $process);
    }
}
