<?php

declare(strict_types=1);

/**
 * EmployerErrorData is a Data Transfer Object (DTO) that encapsulates error data related to an employer's responsibilities in a production process.
 */

namespace App\ProductionProcess\Application\DTO\Error;

/**
 * EmployerErrorData is a Data Transfer Object (DTO) that encapsulates error information specific to various stages of a production process,
 * highlighting the responsibilities assigned to an employee.
 */
final readonly class EmployerErrorData
{
    /**
     * @param int|null    $responsibleEmployeeId
     * @param string|null $responsibleEmployeeName
     * @param int|null    $total
     * @param int|null    $orderCheckIn
     * @param int|null    $printingCheckIn
     * @param int|null    $glowCheckIn
     * @param int|null    $cuttingCheckIn
     */
    public function __construct(public ?int $responsibleEmployeeId = null, public ?string $responsibleEmployeeName = null, public ?int $total = null, public ?int $orderCheckIn = null, public ?int $printingCheckIn = null, public ?int $glowCheckIn = null, public ?int $cuttingCheckIn = null)
    {
    }
}
