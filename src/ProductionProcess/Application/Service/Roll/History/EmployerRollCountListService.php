<?php

declare(strict_types=1);

/**
 * Class EmployerRollCountListService.
 */

namespace App\ProductionProcess\Application\Service\Roll\History;

use App\ProductionProcess\Application\DTO\EmployerRollCountData;
use App\ProductionProcess\Application\Service\Employee\EmployeeFetcher;
use App\ProductionProcess\Domain\ValueObject\Process;

/**
 * Class EmployerRollCountListService.
 */
final readonly class EmployerRollCountListService
{
    /**
     * @param EmployeeFetcher $employeeFetcher
     */
    public function __construct(private EmployeeFetcher $employeeFetcher)
    {
    }

    /**
     * @param EmployerRollCountData[] $employerRollCountData
     *
     * @return EmployerRollCountData[]
     */
    public function __invoke(array $employerRollCountData): array
    {
        $data = [];

        foreach ($employerRollCountData as $employerCount) {
            $employerName = isset($employerCount['employeeId']) ? $this->employeeFetcher->getById($employerCount['employeeId'])->name : '';

            $data[] = new EmployerRollCountData(
                employeeId: $employerCount['employeeId'],
                employerName: $employerName,
                total: $employerCount['total'],
                orderCheckIn: $employerCount[Process::ORDER_CHECK_IN->value],
                printingCheckIn: $employerCount[Process::PRINTING_CHECK_IN->value],
                glowCheckIn: $employerCount[Process::GLOW_CHECK_IN->value],
                cuttingCheckIn: $employerCount[Process::CUTTING_CHECK_IN->value]
            );
        }

        return $data;
    }
}
