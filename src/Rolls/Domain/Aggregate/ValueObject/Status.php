<?php

declare(strict_types=1);

namespace App\Rolls\Domain\Aggregate\Order\ValueObject;

enum Status: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
