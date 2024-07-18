<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Query\FindRolls;

use App\Rolls\Application\DTO\RollData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FindRollsResult
{
    /**
     * Class constructor.
     *
     * @param RollData[] $rollData the roll data
     */
    public function __construct(public array $rollData)
    {
    }
}
