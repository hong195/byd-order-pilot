<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Adapter\Employee;

interface EmployeeAdapterInterface
{
    public function fetchById(string $employeeId): mixed;

    public function fetchByIds(array $employeeIds): array;
}
