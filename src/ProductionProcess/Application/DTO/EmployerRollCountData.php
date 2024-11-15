<?php

declare(strict_types=1);

/**
 * Represents the roll count data for an employee.
 */

namespace App\ProductionProcess\Application\DTO;

/**
 * Represents the roll count data for an employee.
 */
final class EmployerRollCountData
{
    /**
     * Constructor to initialize the class with employee and check-in details.
     *
     * @param int|null    $employeeId      the ID of the employee, or null if not available
     * @param string|null $employeeName    the name of the employee, or null if not available
     * @param int|null    $total           the total value, or null if not available
     * @param int|null    $orderCheckIn    the order check-in value, or null if not available
     * @param int|null    $printingCheckIn the printing check-in value, or null if not available
     * @param int|null    $glowCheckIn     the glow check-in value, or null if not available
     * @param int|null    $cuttingCheckIn  the cutting check-in value, or null if not available
     */
    public function __construct(public readonly ?int $employeeId = null, public readonly ?string $employeeName = null, public readonly ?int $total = null, public ?int $orderCheckIn = null, public ?int $printingCheckIn = null, public ?int $glowCheckIn = null, public ?int $cuttingCheckIn = null)
    {
    }
}
