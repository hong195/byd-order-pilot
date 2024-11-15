<?php

/**
 * @param int|null                $employeeId the ID of the employee to filter by
 * @param Process|null            $process    the process to filter by
 * @param \DateTimeImmutable|null $from       the starting date of the filter range
 * @param \DateTimeImmutable|null $to         the ending date of the filter range
 */

namespace App\ProductionProcess\Domain\Repository\Roll\RollHistory;

use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Repository\DateRangeFilter;

/**
 * Class RollHistoryStatisticsFilter.
 *
 * Represents a filter for roll history statistics, allowing optional filtering
 * by employee ID, process, and date range.
 */
final class FetchRollHistoryStatisticsFilter
{
    /**
     * @param int|null                $employeeId
     * @param Process|null            $process
     * @param \DateTimeImmutable|null $from
     * @param \DateTimeImmutable|null $to
     * @param DateRangeFilter|null    $dateRangeFilter
     */
    public function __construct(public ?int $employeeId = null, public ?Process $process = null, ?\DateTimeImmutable $from = null, ?\DateTimeImmutable $to = null, public ?DateRangeFilter $dateRangeFilter = null)
    {
        $this->dateRangeFilter = new DateRangeFilter($from, $to);
    }
}
