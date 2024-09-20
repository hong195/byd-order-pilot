<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindARoll;

use App\Orders\Application\DTO\Roll\RollData;

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
