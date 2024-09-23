<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindRollHistory;

use App\ProductionProcess\Application\DTO\HistoryData;

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
