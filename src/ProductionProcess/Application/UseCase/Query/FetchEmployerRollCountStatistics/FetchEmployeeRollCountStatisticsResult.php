<?php

declare(strict_types=1);

/**
 * Represents the result of fetching roll history statistics.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FetchEmployerRollCountStatistics;

use App\ProductionProcess\Application\DTO\EmployeeRollCountData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FetchEmployeeRollCountStatisticsResult
{
    /**
     * @param EmployeeRollCountData[] $employeeRollCount the list of employees roll count data object
     */
    public function __construct(public array $employeeRollCount)
    {
    }
}
