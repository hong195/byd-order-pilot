<?php

declare(strict_types=1);

namespace App\Orders\Application\Service\Employee;

use App\Orders\Application\DTO\EmployeeData;
use Doctrine\Common\Collections\Collection;

interface EmployeeFetcher
{
    /**
     * Retrieves employee data by ID.
     *
     * @param int $employeeId the ID of the employee to retrieve data for
     *
     * @return EmployeeData the data of the employee with the provided ID
     */
    public function getById(int $employeeId): EmployeeData;

    /**
     * Retrieves employees by their ids.
     *
     * @param array $employeeIds the array of employee ids
     *
     * @return Collection<EmployeeData> the array of employees
     */
    public function getByIds(array $employeeIds): Collection;
}
