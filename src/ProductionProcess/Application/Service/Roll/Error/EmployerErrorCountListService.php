<?php

declare(strict_types=1);

/**
 * Class EmployerRollCountListService.
 */

namespace App\ProductionProcess\Application\Service\Roll\Error;

use App\ProductionProcess\Application\DTO\Error\EmployeeErrorData;
use App\ProductionProcess\Application\DTO\Error\EmployeeErrorDataTransformer;
use App\ProductionProcess\Application\Service\Employee\EmployeeFetcher;

/**
 * Class EmployerRollCountListService.
 *
 * The purpose of this service is to add responsible employee name to EmployeeErrorData
 */
final readonly class EmployerErrorCountListService
{
    /**
     * @param EmployeeFetcher              $employeeFetcher
     * @param EmployeeErrorDataTransformer $dataTransformer
     */
    public function __construct(private EmployeeFetcher $employeeFetcher, private EmployeeErrorDataTransformer $dataTransformer)
    {
    }

    /**
     * Purpose is to add responsible employee name to EmployeeErrorData.
     *
     * @param EmployeeErrorData[] $employeeErrorCountData
     *
     * @return EmployeeErrorData[]
     */
    public function __invoke(array $employeeErrorCountData): array
    {
        $data = [];

        $responsibleEmployeeIds = [];
        $employees = [];

        foreach ($employeeErrorCountData as $employeeCount) {
            if (isset($employeeCount->responsibleEmployeeId)) {
                $responsibleEmployeeIds[] = $employeeCount->responsibleEmployeeId;
            }
        }

        if (count($responsibleEmployeeIds)) {
            $employeeArray = $this->employeeFetcher->getByIds(array_unique($responsibleEmployeeIds));

            foreach ($employeeArray as $employee) {
                $employees[$employee->id] = $employee;
            }
        }

        foreach ($employeeErrorCountData as $employeeCount) {
            $responsibleEmployeeName = null;

            if (isset($employeeCount->responsibleEmployeeId) && isset($employees[$employeeCount->responsibleEmployeeId])) {
                $responsibleEmployeeName = $employees[$employeeCount->responsibleEmployeeId]->name;
            }

            // Creating the EmployeeErrorData object with employee name
            $data[] = new EmployeeErrorData(
                responsibleEmployeeId: $employeeCount->responsibleEmployeeId,
                responsibleEmployeeName: $responsibleEmployeeName,
                total: $employeeCount->total,
                orderCheckIn: $employeeCount->orderCheckIn,
                printingCheckIn: $employeeCount->printingCheckIn,
                glowCheckIn: $employeeCount->glowCheckIn,
                cuttingCheckIn: $employeeCount->cuttingCheckIn
            );
        }

        return $this->dataTransformer->fromErrorsList($data);
    }
}
