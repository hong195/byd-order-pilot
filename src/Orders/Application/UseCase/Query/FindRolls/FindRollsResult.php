<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindRolls;

use App\Orders\Application\DTO\OrderStackData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FindRollsResult
{
    /**
     * Class constructor.
     *
     * @param OrderStackData[] $items the roll data
     */
    public function __construct(public array $items)
    {
    }
}
