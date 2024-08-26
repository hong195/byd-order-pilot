<?php

declare(strict_types=1);

namespace App\Orders\Domain\ValueObject;

/**
 * Enum Status represents the possible statuses of an order and order stack.
 */
enum Status: string
{
    case ASSIGNABLE = 'assignable';
    case UNASSIGNED = 'unassigned';

    case ASSIGNED = 'assigned';

    case CONFLICT = 'conflict';

    case SHIP_AND_COLLECT = 'ship_and_collect';

    /**
     * Check if the given status is equal to the current status.
     *
     * @param self $status the status to compare
     *
     * @return bool true if the statuses are equal, false otherwise
     */
    public function equals(self $status): bool
    {
        return $this === $status;
    }
}
