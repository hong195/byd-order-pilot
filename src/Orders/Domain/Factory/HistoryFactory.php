<?php

declare(strict_types=1);

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Roll\History\History;
use App\Orders\Domain\Aggregate\Roll\History\Type;
use App\Orders\Domain\ValueObject\Process;

/**
 * Class HistoryFactory.
 *
 * This class is responsible for creating a new History object from a Roll object.
 */
final class HistoryFactory
{
    /**
     * Creates a new history record.
     *
     * @param int                $rollId       the ID of the roll
     * @param Process            $process      the process associated with the history
     * @param \DateTimeImmutable $happenedAt   the datetime when the history occurred
     * @param Type               $type         the type of the history
     * @param int|null           $parentRollId the ID of the parent roll, if any
     * @param int|null           $employeeId   the ID of the employee, if any
     *
     * @return History the created history record
     */
    public function make(int $rollId, Process $process, \DateTimeImmutable $happenedAt, Type $type, ?int $parentRollId = null, ?int $employeeId = null): History
    {
        $history = new History(rollId: $rollId, process: $process, type: $type, happenedAt: $happenedAt);

        if ($employeeId) {
            $history->setEmployeeId($employeeId);
        }

		if ($parentRollId) {
			$history->setParentRollId($parentRollId);
		}

        return $history;
    }
}
