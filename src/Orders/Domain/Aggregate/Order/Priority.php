<?php

namespace App\Orders\Domain\Aggregate\Order;

/**
 * Enum representing priority levels.
 *
 * @method static Priority STANDARD()
 * @method static Priority HIGH()
 * @method static Priority LOW()
 */
enum Priority: string
{
    case HIGH = 'high';
    case STANDARD = 'standard';
    case LOW = 'low';

    /**
     * Get the priority sort.
     *
     * @return array<string, int> the array containing the priority sort
     */
    public function getPrioritySort(): array
    {
        return [Priority::HIGH->value => 1, Priority::STANDARD->value => 2, Priority::LOW->value => 3];
    }
}
