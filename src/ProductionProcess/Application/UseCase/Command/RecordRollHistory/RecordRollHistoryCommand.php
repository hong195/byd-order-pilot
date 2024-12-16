<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\RecordRollHistory;

use App\ProductionProcess\Domain\Aggregate\Roll\History\Type;
use App\ProductionProcess\Domain\ValueObject\Process;
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
     * @param string $rollId - The ID of the roll
     */
    public function __construct(public readonly string $rollId, public readonly Process $process)
    {
        $this->type = Type::PROCESS_CHANGED;
    }

    public function getType(): Type
    {
        return $this->type;
    }
}
