<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO\Order;

/**
 * Class SortOrderData.
 *
 * Represents data related to sort orders.
 */
final readonly class SortOrderData
{
    /**
     * Initializes a new instance of the class.
     *
     * @param int   $rollId     the roll id
     * @param int   $group      the group
     * @param int[] $sortOrders the sort orders
     */
    public function __construct(public int $rollId, public int $group, public array $sortOrders)
    {
    }
}
