<?php

declare(strict_types=1);

/**
 * Represents the result of fetching roll history statistics.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FetchEmployerRollCountStatistics;

use App\ProductionProcess\Application\DTO\EmployerRollCountData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FetchEmployerRollCountStatisticsResult
{
    /**
     * @param EmployerRollCountData[] $employerRollCount the list of employers roll count data object
     */
    public function __construct(public array $employerRollCount)
    {
    }
}
