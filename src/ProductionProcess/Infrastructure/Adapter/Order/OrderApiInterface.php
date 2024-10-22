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
     * @param int $productId the ID of the employee to find
     *
     * @return mixed the employee data if found, or null if not found
     */
    public function findProductById(int $productId): mixed;

    /**
     * Find an employee by their ID.
     *
     * @param int[] $productIds the ID of the employee to find
     *
     * @return mixed the employee data if found, or null if not found
     */
    public function findProductByIds(array $productIds): mixed;
}
