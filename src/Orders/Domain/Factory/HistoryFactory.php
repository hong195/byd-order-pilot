<?php

declare(strict_types=1);

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Roll\History;
use App\Orders\Domain\Aggregate\Roll\Roll;

/**
 * Class HistoryFactory.
 *
 * This class is responsible for creating a new History object from a Roll object.
 */
final class HistoryFactory
{
    private ?History $history = null;

    /**
     * Takes a Roll object and returns a new History object with the given rollId, process, and startedAt values.
     *
     * @param Roll $roll the Roll object to retrieve data from
     */
    public function fromRoll(Roll $roll): void
    {
        $startedAt = \DateTimeImmutable::createFromInterface($roll->getDateAdded());

        $this->history = new History(rollId: $roll->getId(), process: $roll->getProcess(), startedAt: $startedAt);
    }

    /**
     * Sets the parent roll id for the history object.
     *
     * @param int $parentRollId the parent roll id to set
     */
    public function withParentRollId(int $parentRollId): void
    {
        $this->history->setParentRollId($parentRollId);
    }

    /**
     * Returns the history object.
     *
     * @return History|null the history object if it exists, null otherwise
     */
    public function build(): ?History
    {
        return $this->history;
    }
}
