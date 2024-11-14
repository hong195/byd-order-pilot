<?php

declare(strict_types=1);

/**
 * Class EmployerRollCountListService.
 */

namespace App\ProductionProcess\Application\Service\Roll\Error;

use App\ProductionProcess\Application\DTO\Error\EmployerErrorData;
use App\ProductionProcess\Application\DTO\Error\EmployerErrorDataTransformer;
use App\ProductionProcess\Application\Service\Employee\EmployeeFetcher;
use App\ProductionProcess\Domain\ValueObject\Process;

/**
 * Class EmployerRollCountListService.
 */
final readonly class EmployerErrorCountListService
{
    /**
     * @param EmployeeFetcher              $employeeFetcher
     * @param EmployerErrorDataTransformer $dataTransformer
     */
    public function __construct(private EmployeeFetcher $employeeFetcher, private EmployerErrorDataTransformer $dataTransformer)
    {
    }

    /**
     * @param EmployerErrorData[] $employerErrorCountData
     *
     * @return EmployerErrorData[]
     */
    public function __invoke(array $employerErrorCountData): array
    {
        $data = [];

        foreach ($employerErrorCountData as $employerCount) {
            $responsibleEmployerName = isset($employerCount['responsibleEmployeeId']) ? $this->employeeFetcher->getById($employerCount['responsibleEmployeeId'])->name : null;

            $data[] = new EmployerErrorData(
                responsibleEmployeeId: $employerCount['responsibleEmployeeId'],
                responsibleEmployeeName: $responsibleEmployerName,
                total: $employerCount['total'],
                orderCheckIn: $employerCount[Process::PRINTING_CHECK_IN->value],
                printingCheckIn: $employerCount[Process::PRINTING_CHECK_IN->value],
                glowCheckIn: $employerCount[Process::GLOW_CHECK_IN->value],
                cuttingCheckIn: $employerCount[Process::CUTTING_CHECK_IN->value],
            );
        }

        return $this->dataTransformer->fromErrorsList($data);
    }
}
