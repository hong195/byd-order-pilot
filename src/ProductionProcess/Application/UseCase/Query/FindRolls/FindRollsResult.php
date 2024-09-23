<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindRolls;

use App\ProductionProcess\Application\DTO\Roll\RollData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FindRollsResult
{
    /**
     * Class constructor.
     *
     * @param RollData[] $items the roll data
     */
    public function __construct(public array $items)
    {
    }
}
