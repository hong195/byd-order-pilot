<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\AssignOrder;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to change the priority of an order.
 */
readonly class AssignOrderCommand implements CommandInterface
{
    /**
     * Constructor.
     *
     * @param int $id the ID of the object
     */
    public function __construct(public int $id)
    {
    }
}
