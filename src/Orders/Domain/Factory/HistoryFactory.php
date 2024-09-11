<?php

declare(strict_types=1);

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Roll\History;
use App\Orders\Domain\Aggregate\Roll\Roll;
use App\Orders\Domain\ValueObject\Process;

/**
 * Class HistoryFactory.
 *
 * This class is responsible for creating a new History object from a Roll object.
 */
final class HistoryFactory
{
    /**
     * Takes a Roll object and returns a new History object with the given rollId, process, and startedAt values.
     *
     * @param Roll $roll the Roll object to retrieve data from
     */
    public function fromRoll(Roll $roll): History
    {
        $startedAt = \DateTimeImmutable::createFromInterface($roll->getDateAdded());

        $history = new History(rollId: $roll->getId(), process: $roll->getProcess(), happenedAt: $startedAt);

        if ($roll->getEmployeeId()) {
            $history->setEmployeeId($roll->getEmployeeId());
        }

        return $history;
    }

    /**
     * Creates a new instance of History.
     *
     * @param int                $rollId     the ID of the roll
     * @param Process            $process    the process object
     * @param \DateTimeImmutable $startedAt  the date and time when the history entry started
     * @param int|null           $employeeId the ID of the employee (optional)
     *
     * @return History the newly created History instance
     */
    public function make(int $rollId, Process $process, \DateTimeImmutable $startedAt, ?int $employeeId = null): History
    {
        $history = new History(rollId: $rollId, process: $process, happenedAt: $startedAt);

        if ($employeeId) {
            $history->setEmployeeId($employeeId);
        }

        return $history;
    }
}
