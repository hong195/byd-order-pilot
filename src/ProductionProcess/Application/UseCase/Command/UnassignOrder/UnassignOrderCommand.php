<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UnassignOrder;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to change the priority of an order.
 */
readonly class UnassignOrderCommand implements CommandInterface
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
