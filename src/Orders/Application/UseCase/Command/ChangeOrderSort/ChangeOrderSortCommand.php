<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\ChangeOrderSort;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to change the priority of an order.
 */
readonly class ChangeOrderSortCommand implements CommandInterface
{
    /**
     * Constructor for the class.
     *
     * @param int        $rollId     The ID of the roll
     * @param int        $group      The group number
     * @param array<int> $sortOrders The sort orders for the roll
     */
    public function __construct(public int $rollId, public int $group, public array $sortOrders)
    {
    }
}
