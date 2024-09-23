<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO;

/**
 * Class EmployeeData.
 *
 * Represents the data of an employee.
 */
final readonly class EmployeeData
{
    /**
     * Constructs a new instance of the class.
     *
     * @param int|null    $id   the id
     * @param string|null $name the name
     */
    public function __construct(public ?int $id = null, public ?string $name = null)
    {
    }
}
