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

namespace App\ProductionProcess\Domain\Repository\PrintedProduct\Error;

use App\Shared\Domain\Repository\DateRangeFilter;

/**
 * Filter class for getting Employee errors.
 */
final class ErrorFilter
{
	/**
	 * Constructor for the Symfony application.
	 *
	 * @param int|null $responsibleEmployeeId The ID of the responsible employee.
	 * @param int|null $noticerId The ID of the noticer.
	 * @param string|null $process The process related to the application.
	 * @param DateRangeFilter|null $dateRangeFilter An optional filter for date ranges.
	 */
	public function __construct(public ?int $responsibleEmployeeId = null, public ?int $noticerId = null, public ?string $process = null, public ?DateRangeFilter $dateRangeFilter = null)
    {
    }
}
