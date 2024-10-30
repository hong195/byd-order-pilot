<?php

/**
 * @param int|null                $employeeId the ID of the employee to filter by
 * @param Process|null            $process    the process to filter by
 * @param \DateTimeImmutable|null $from       the starting date of the filter range
 * @param \DateTimeImmutable|null $to         the ending date of the filter range
 */

namespace App\ProductionProcess\Application\UseCase\Query\FetchRollHistoryStatistics;

use App\ProductionProcess\Domain\ValueObject\Process;

/**
 * Class RollHistoryStatisticsFilter.
 *
 * Represents a filter for roll history statistics, allowing optional filtering
 * by employee ID, process, and date range.
 */
final readonly class RollHistoryStatisticsFilter
{
    /**
     * @param int|null                $employeeId
     * @param Process|null            $process
     * @param \DateTimeImmutable|null $from
     * @param \DateTimeImmutable|null $to
     */
    public function __construct(
        public ?int $employeeId = null,
        public ?Process $process = null,
        public ?\DateTimeImmutable $from = null,
        public ?\DateTimeImmutable $to = null,
    ) {
    }

    /**
     * @return int|null
     */
    public function getEmployeeId(): ?int
    {
        return $this->employeeId;
    }

    /**
     * @return Process|null
     */
    public function getProcess(): ?Process
    {
        return $this->process;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getFrom(): ?\DateTimeImmutable
    {
        return $this->from;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getTo(): ?\DateTimeImmutable
    {
        return $this->to;
    }
}
