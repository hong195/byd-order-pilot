<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\AssignEmployeeToRoll;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to change the priority of an order.
 */
readonly class AssignEmployeeToRollCommand implements CommandInterface
{
    /**
     * Class constructor.
     *
     * @param string $rollId     - The ID of the roll
     * @param string $employeeId - The ID of the employee
     */
    public function __construct(public string $rollId, public string $employeeId)
    {
    }
}
