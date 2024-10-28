<?php

/**
 * @param int|null                $employeeId the ID of the employee to filter by
 * @param Process|null            $process    the process to filter by
 * @param \DateTimeImmutable|null $from       the starting date of the filter range
 * @param \DateTimeImmutable|null $to         the ending date of the filter range
 */

namespace App\ProductionProcess\Application\UseCase\Query\FetchRollHistoryStatistics;

use App\ProductionProcess\Domain\ValueObject\Process;

final readonly class RollHistoryStatisticsFilterCriteria
{
    public function __construct(
        public ?int $employeeId = null,
        public ?Process $process = null,
        public ?\DateTimeImmutable $from = null,
        public ?\DateTimeImmutable $to = null,
    ) {
    }

    public function getEmployeeId(): ?int
    {
        return $this->employeeId;
    }

    public function getProcess(): ?Process
    {
        return $this->process;
    }

    public function getFrom(): ?\DateTimeImmutable
    {
        return $this->from;
    }

    public function getTo(): ?\DateTimeImmutable
    {
        return $this->to;
    }
}
