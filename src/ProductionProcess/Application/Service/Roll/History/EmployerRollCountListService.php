<?php

declare(strict_types=1);

/**
 * Class EmployerRollCountListService.
 */

namespace App\ProductionProcess\Application\Service\Roll\History;

use App\ProductionProcess\Application\DTO\EmployerRollCountData;
use App\ProductionProcess\Application\Service\Employee\EmployeeFetcher;

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
     * Added employee name into EmployerRollCountData.
     *
     * @param EmployerRollCountData[] $employerRollCountData
     *
     * @return EmployerRollCountData[]
     */
    public function __invoke(array $employerRollCountData): array
    {
        $data = [];

        $employeeIds = [];
        $employees = [];

        foreach ($employerRollCountData as $employeeCount) {
            if (isset($employeeCount->employeeId)) {
                $employeeIds[] = $employeeCount->employeeId;
            }
        }

        if (count($employeeIds)) {
            $employeeArray = $this->employeeFetcher->getByIds(array_unique($employeeIds));

            foreach ($employeeArray as $employee) {
                $employees[$employee->id] = $employee;
            }
        }


        foreach ($employerRollCountData as $employeeCount) {
            $employeeName = null;

            if (isset($employeeCount->employeeId) && isset($employees[$employeeCount->employeeId])) {
                $employeeName = $employees[$employeeCount->employeeId]->name;
            }

            $data[] = new EmployerRollCountData(
                employeeId: $employeeCount->employeeId,
                employeeName: $employeeName,
                total: $employeeCount->total,
                orderCheckIn: $employeeCount->orderCheckIn,
                printingCheckIn: $employeeCount->printingCheckIn,
                glowCheckIn: $employeeCount->glowCheckIn,
                cuttingCheckIn: $employeeCount->cuttingCheckIn
            );
        }

        return $data;
    }
}
