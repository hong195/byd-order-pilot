<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Adapter\Employee;

use App\Orders\Application\DTO\EmployeeData;
use App\Orders\Application\Service\Employee\EmployeeFetcher;
use App\Users\Infrastructure\Api\EmployeeApi;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final readonly class EmployeeAdapter implements EmployeeFetcher
{
    public function __construct(private EmployeeApi $employeeApi)
    {
    }

    /**
     * Fetches employee data by their ID.
     *
     * @param int $employeeId The ID of the employee
     *
     * @return EmployeeData The EmployeeData object
     */
    public function getById(int $employeeId): EmployeeData
    {
        $employee = $this->employeeApi->fetchById($employeeId);

        return new EmployeeData(id: $employee->id, name: $employee->name);
    }

    /**
     * Fetches employee data by their IDs.
     *
     * @param array $employeeIds An array of employee IDs
     *
     * @return Collection<EmployeeData> An array of EmployeeData objects
     */
    public function getByIds(array $employeeIds): Collection
    {
        $employees = $this->employeeApi->fetchByIds($employeeIds);
        $employeeData = new ArrayCollection();

        foreach ($employees as $employee) {
            $employeeData->add(new EmployeeData(id: $employee->id, name: $employee->name));
        }

        return $employeeData;
    }
}
