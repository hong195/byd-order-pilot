<?php

declare(strict_types=1);

/**
 * ErrorFilter constructor.
 *
 * @param int|null                $responsibleEmployeeId the ID of the responsible employee
 * @param int|null                $noticerId             the ID of the noticer
 * @param string|null             $process               the process name
 * @param \DateTimeImmutable|null $from                  the start date and time
 * @param \DateTimeImmutable|null $to                    the end date and time
 */

namespace App\ProductionProcess\Domain\Repository\Statistics\Errors;

use App\Shared\Domain\Repository\DateRangeFilter;

/**
 * Filter class for getting Employee errors.
 */
final class ErrorFilter
{
    /**
     * @param int|null                $responsibleEmployeeId
     * @param int|null                $noticerId
     * @param string|null             $process
     * @param \DateTimeImmutable|null $from
     * @param \DateTimeImmutable|null $to
     * @param DateRangeFilter|null    $dateRangeFilter
     */
    public function __construct(public ?int $responsibleEmployeeId = null, public ?int $noticerId = null, public ?string $process = null, ?\DateTimeImmutable $from = null, ?\DateTimeImmutable $to = null, public ?DateRangeFilter $dateRangeFilter = null)
    {
        $this->dateRangeFilter = new DateRangeFilter($from, $to);
    }
}
