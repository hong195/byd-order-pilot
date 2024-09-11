<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindRollHistory;

use App\Orders\Application\DTO\HistoryData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FindRollHistoryResult
{
    /**
     * Class constructor.
     *
     * @param HistoryData[] $items the roll data
     */
    public function __construct(public array $items)
    {
    }
}
