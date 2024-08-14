<?php

declare(strict_types=1);

namespace App\Orders\Domain\ValueObject;

/**
 * Enum Status represents the possible statuses of an order and order stack.
 */
enum Status: string
{
    case NEW = 'new';
    case UNASSIGNED = 'unassigned';

    case ASSIGNED = 'assigned';

    case CONFLICT = 'conflict';

    /**
     * Check if the current instance is unassignable.
     *
     * @return bool returns true if the instance is unassignable, otherwise false
     */
    public function unassignable(): bool
    {
        return in_array($this, [$this]);
    }
}
