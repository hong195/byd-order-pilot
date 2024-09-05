<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UnAssignEmployeeFromRoll;

use App\Shared\Application\Command\CommandInterface;

/**
 * Class UnAssignEmployeeFromRollCommand.
 *
 * Represents a command to unassign an employee from a roll.
 */
readonly class UnAssignEmployeeFromRollCommand implements CommandInterface
{
    /**
     * Class constructor.
     *
     * @param int $rollId - The ID of the roll
     */
    public function __construct(public int $rollId)
    {
    }
}
