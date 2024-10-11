<?php

/**
 * Data Transfer Object representing the historical statistics for a roll.
 *
 * @param int                $rollId     the id of the roll
 * @param Process            $process    the production process
 * @param string             $type       the type of the process
 * @param \DateTimeImmutable $happenedAt the date and time when the event happened
 */

namespace App\ProductionProcess\Domain\DTO;

use App\ProductionProcess\Domain\ValueObject\Process;

/**
 * @param int                $rollId
 * @param Process            $process
 * @param string             $type
 * @param \DateTimeImmutable $happenedAt
 */
final readonly class RollHistoryStatistics
{
    /**
     * @param int                $rollId
     * @param Process            $process
     * @param string             $type
     * @param \DateTimeImmutable $happenedAt
     */
    public function __construct(
        private int $rollId,
        private Process $process,
        private string $type,
        private \DateTimeImmutable $happenedAt
    ) {
    }

    /**
     * Returns the roll ID.
     * @return int
     */
    public function getRollId(): int
    {
        return $this->rollId;
    }

    /**
     * Returns the process as a string.
     * @return string
     */
    public function getProcess(): string
    {
        return $this->process->value;
    }

    /**
     * Returns the type of the process.
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Returns the happenedAt value as an associative array.
     * @return string
     */
    public function getHappenedAt(): string
    {
        return $this->happenedAt->format('Y-m-d H:i:s');
    }
}
