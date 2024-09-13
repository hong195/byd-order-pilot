<?php

declare(strict_types=1);

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Roll\History\History;
use App\Orders\Domain\Aggregate\Roll\History\Type;
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
     * Creates a new instance of History.
     *
     * @param int                $rollId     the ID of the roll
     * @param Process            $process    the process object
     * @param \DateTimeImmutable $happenedAt  the date and time when the history entry started
     * @param int|null           $employeeId the ID of the employee (optional)
     *
     * @return History the newly created History instance
     */
    public function make(int $rollId, Process $process, \DateTimeImmutable $happenedAt, Type $type, ?int $employeeId = null): History
    {
        $history = new History(rollId: $rollId, process: $process, type: $type, happenedAt: $happenedAt);

        if ($employeeId) {
            $history->setEmployeeId($employeeId);
        }

        return $history;
    }
}
