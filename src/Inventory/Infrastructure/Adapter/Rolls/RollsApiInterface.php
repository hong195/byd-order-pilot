<?php

namespace App\Inventory\Infrastructure\Adapter\Rolls;

use App\Inventory\Application\UseCases\Query\DTO\RollData;

/**
 * Interface RollsApiInterface.
 *
 * This interface represents an adapter for retrieving RollData objects.
 */
interface RollsApiInterface
{
    /**
     * Gets a RollData object by its ID.
     *
     * @param int $rollId the ID of the roll
     *
     * @return RollData the RollData object with the given ID
     */
    public function getRollById(int $rollId): RollData;
}
