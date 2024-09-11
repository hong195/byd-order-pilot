<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\CopyRollHistory;

use App\Shared\Application\Command\CommandInterface;

/**
 * Class RecordRollHistoryCommand.
 *
 * Represents a command to unassign an employee from a roll.
 */
readonly class CopyRollHistoryCommand implements CommandInterface
{
    /**
     * Class constructor.
     *
     * @param int        $rollId     the roll ID
     * @param array<int> $newRollIds an array of new roll IDs
     */
    public function __construct(public int $rollId, public array $newRollIds)
    {
    }
}
