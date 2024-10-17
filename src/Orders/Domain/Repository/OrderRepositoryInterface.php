<?php

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\Aggregate\Order;
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
     * Finds all orders that are ready for packing.
     *
     * @return Order[] an array of orders that are ready for packing
     */
    public function findReadyForPacking(): array;

    /**
     * Finds orders that are either partially packed or not packed at all.
     *
     * @return Order[] an array of orders that are partially packed or not packed at all
     */
    public function findPartiallyPacked(): array;

    /**
     * Finds all orders with extras included.
     *
     * @return Order[] an array of orders with extras included
     */
    public function findOnlyWithExtras(): array;

    /**
     * Finds queried records.
     *
     * @return PaginationResult the result of the queried records
     */
    public function findByFilter(OrderFilter $filter): PaginationResult;
}
