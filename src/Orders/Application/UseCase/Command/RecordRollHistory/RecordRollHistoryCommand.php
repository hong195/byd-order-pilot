<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\RecordRollHistory;

use App\Orders\Domain\Aggregate\Roll\History\Type;
use App\Shared\Application\Command\CommandInterface;

/**
 * Class RecordRollHistoryCommand.
 *
 * Represents a command to unassign an employee from a roll.
 */
class RecordRollHistoryCommand implements CommandInterface
{
    private Type $type;

    /**
     * Class constructor.
     *
     * @param int $rollId - The ID of the roll
     */
    public function __construct(public readonly int $rollId)
    {
        $this->type = Type::PROCESS_CHANGED;
    }

    public function getType(): Type
    {
        return $this->type;
    }
}
