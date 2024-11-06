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

namespace App\ProductionProcess\Domain\Repository;

final readonly class ErrorFilter
{
    /**
     * @param int|null                  $responsibleEmployeeId
     * @param int|null                  $noticerId
     * @param string|null               $process
     * @param \DateTimeImmutable|null   $from
     * @param \DateTimeImmutable|null   $to
     */
    public function __construct(public ?int $responsibleEmployeeId = null, public ?int $noticerId = null, public ?string $process = null, public ?\DateTimeImmutable $from = null, public ?\DateTimeImmutable $to = null)
    {
    }
}
