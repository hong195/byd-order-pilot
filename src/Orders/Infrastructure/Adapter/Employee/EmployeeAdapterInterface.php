<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Adapter\Employee;

interface EmployeeAdapterInterface
{
    public function fetchById(int $employeeId): mixed;

    public function fetchByIds(array $employeeIds): array;
}
