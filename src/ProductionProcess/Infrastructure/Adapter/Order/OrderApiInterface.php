<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Adapter\Order;

/**
 * OrderApiInterface is an interface for finding an employee by their ID.
 */
interface OrderApiInterface
{
    /**
     * Find an employee by their ID.
     *
     * @param int $employeeId the ID of the employee to find
     *
     * @return mixed the employee data if found, or null if not found
     */
    public function findProductById(int $employeeId): mixed;
}
