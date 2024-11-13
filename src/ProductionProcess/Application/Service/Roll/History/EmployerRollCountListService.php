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
                orderCheckIn: $employerCount['order_check_in'],
                printingCheckIn: $employerCount['print_check_in'],
                glowCheckIn: $employerCount['glow_check_in'],
                cuttingCheckIn: $employerCount['cut_check_in']
            );
        }

        return $data;
    }
}
