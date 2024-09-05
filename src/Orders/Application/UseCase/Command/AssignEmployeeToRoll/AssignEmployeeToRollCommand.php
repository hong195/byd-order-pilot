<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\AssignEmployeeToRoll;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to change the priority of an order.
 */
readonly class AssignEmployeeToRollCommand implements CommandInterface
{
    /**
     * Class constructor.
     *
     * @param int $rollId     - The ID of the roll
     * @param int $employeeId - The ID of the employee
     */
    public function __construct(public int $rollId, public int $employeeId)
    {
    }
}
