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
}
