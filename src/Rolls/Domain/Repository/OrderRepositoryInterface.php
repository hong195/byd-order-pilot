<?php

namespace App\Rolls\Domain\Repository;

use App\Rolls\Domain\Aggregate\Order\Order;
use App\Shared\Domain\Repository\PaginationResult;

interface OrderRepositoryInterface
{
    /**
     * Saves an order.
     *
     * @param Order $order the order to save
     */
    public function save(Order $order): void;

    /**
     * Finds an order by its ID.
     *
     * @param int $id the ID of the order to find
     *
     * @return Order|null the found order if exists, otherwise null
     */
    public function findById(int $id): ?Order;

    /**
     * Finds queried records.
     *
     * @return PaginationResult the result of the queried records
     */
    public function findQueried(): PaginationResult;
}
