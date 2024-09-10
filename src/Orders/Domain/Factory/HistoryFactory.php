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

		$history = new History(rollId: $roll->getId(), process: $roll->getProcess(), startedAt: $startedAt);

		if ($roll->getEmployeeId()) {
			$history->setEmployeeId($roll->getEmployeeId());
		}

		return $history;
    }

	public function make(int $rollId, Process $process, \DateTimeImmutable $startedAt): History
	{
		return new History(rollId: $rollId, process: $process, startedAt: $startedAt);
	}
}
