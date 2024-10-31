<?php

declare(strict_types=1);

/**
 * Represents the result of fetching roll history statistics.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FetchRollHistoryStatistics;

use App\ProductionProcess\Application\DTO\HistoryData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FetchRollHistoryStatisticsResult
{
    /**
     * @param HistoryData[] $rollHistoryData the roll data object
     */
    public function __construct(public array $rollHistoryData)
    {
    }
}
