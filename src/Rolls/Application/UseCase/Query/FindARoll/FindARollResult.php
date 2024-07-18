<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Query\FindARoll;

use App\Rolls\Application\DTO\RollData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FindARollResult
{
    /**
     * @param RollData $rollData the roll data object
     *
     * @return void
     */
    public function __construct(public RollData $rollData)
    {
    }
}
