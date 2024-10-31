<?php

declare(strict_types=1);

/**
 * Represents the result of fetching roll history statistics.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FetchRollHistoryStatistics;

use App\ProductionProcess\Domain\Aggregate\Roll\History\History;

/**
 * Represents the result of finding a roll.
 */
final readonly class FetchRollHistoryStatisticsResult
{
    /**
     * @param History[] $rollHistoryData the roll data object
     *
     * @return void
     */
    public function __construct(public array $rollHistoryData)
    {
    }
}
