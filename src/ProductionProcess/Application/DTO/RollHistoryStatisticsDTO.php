<?php

/**
 * Data Transfer Object representing the historical statistics for a roll.
 *
 * @param int                $rollId     the id of the roll
 * @param Process            $process    the production process
 * @param string             $type       the type of the process
 * @param \DateTimeImmutable $happenedAt the date and time when the event happened
 */

namespace App\ProductionProcess\Application\DTO;

use App\ProductionProcess\Domain\ValueObject\Process;

/**
 * Data Transfer Object representing the historical statistics for a roll.
 *
 * @param int                $rollId     the id of the roll
 * @param Process            $process    the production process
 * @param string             $type       the type of the process
 * @param \DateTimeImmutable $happenedAt the date and time when the event happened
 */
final readonly class RollHistoryStatisticsDTO
{
    public function __construct(
        public string $rollId,
        public Process $process,
        public string $type,
        public \DateTimeImmutable $happenedAt,
    ) {
    }
}
