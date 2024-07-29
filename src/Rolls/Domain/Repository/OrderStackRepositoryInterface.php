<?php

declare(strict_types=1);

namespace App\Rolls\Domain\Repository;

use App\Rolls\Domain\Aggregate\OrderStack\OrderStack;

interface OrderStackRepositoryInterface
{
    /**
     * Saves an OrderStack to the database.
     *
     * @param OrderStack $orderStack the OrderStack object to be saved
     */
    public function save(OrderStack $orderStack): void;

    /**
     * Finds an OrderStack by its ID.
     *
     * @param string $id the ID of the OrderStack to find
     *
     * @return OrderStack|null the found OrderStack, or null if not found
     */
    public function findById(string $id): ?OrderStack;

    /**
     * Retrieves all records from the database and returns them as an array.
     *
     * @return OrderStack[] an array containing all database records
     */
    public function findQueried(OrderStackFilter $orderStackFilter): array;
}
