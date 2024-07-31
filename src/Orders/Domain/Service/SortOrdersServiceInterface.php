<?php

namespace App\Orders\Domain\Service;

use Doctrine\Common\Collections\Collection;

/**
 * SortOrdersServiceInterface is an interface that defines a method for sorting a collection of orders by priority.
 *
 */
interface SortOrdersServiceInterface
{
    /**
     * Sorts a collection of orders by a priority.
     *
     * @param Collection $orders the collection of orders to be sorted
     *
     * @return Collection the sorted collection of orders
     */
    public function getSorted(Collection $orders): Collection;
}
