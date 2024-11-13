<?php

declare(strict_types=1);

/**
 * Represents the roll count data for an employer.
 */

namespace App\ProductionProcess\Application\DTO;

/**
 * Represents the roll count data for an employer.
 */
final class EmployerRollCountData
{
    private ?EmployeeData $employee = null;

    /**
     * Constructor to initialize the class with employee and check-in details.
     *
     * @param int|null    $employeeId      the ID of the employee, or null if not available
     * @param string|null $employerName    the name of the employer, or null if not available
     * @param int|null    $total           the total value, or null if not available
     * @param int|null    $orderCheckIn    the order check-in value, or null if not available
     * @param int|null    $printingCheckIn the printing check-in value, or null if not available
     * @param int|null    $glowCheckIn     the glow check-in value, or null if not available
     * @param int|null    $cuttingCheckIn  the cutting check-in value, or null if not available
     */
    public function __construct(public readonly ?int $employeeId = null, public readonly ?string $employerName = null, public readonly ?int $total = null, public ?int $orderCheckIn = null, public ?int $printingCheckIn = null, public ?int $glowCheckIn = null, public ?int $cuttingCheckIn = null)
    {
    }

    /**
     * Set the employee data.
     *
     * @param ?EmployeeData $employeeData the employee data to set
     */
    public function withEmployee(?EmployeeData $employeeData): void
    {
        $this->employee = $employeeData;
    }

    /**
     * Get the employee data.
     *
     * @return EmployeeData|null the employee data, or null if not available
     */
    public function getEmployee(): ?EmployeeData
    {
        return $this->employee;
    }
}
