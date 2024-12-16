<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\ChangePrintedProductPriority;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to change the priority of an order.
 */
readonly class ChangePrintedProductPriorityCommand implements CommandInterface
{
    /**
     * Constructor.
     *
     * @param int  $id       the ID of the object
     * @param bool $priority the priority status of the object
     */
    public function __construct(public string $id, public bool $priority)
    {
    }
}
