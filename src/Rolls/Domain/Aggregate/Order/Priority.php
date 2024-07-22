<?php

namespace App\Rolls\Domain\Aggregate\Order;

/**
 * Enum representing priority levels.
 *
 * @method static Priority STANDARD()
 * @method static Priority HIGH()
 * @method static Priority LOW()
 */
enum Priority: string
{
    case STANDARD = 'standard';
    case HIGH = 'high';
    case LOW = 'low';
}
