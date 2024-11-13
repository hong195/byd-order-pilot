<?php

/**
 * @param \DateTimeImmutable|null $from the starting date of the filter range
 * @param \DateTimeImmutable|null $to   the ending date of the filter range
 */

namespace App\ProductionProcess\Domain\Repository;

/**
 * @param \DateTimeImmutable|null $from the starting date of the filter range
 * @param \DateTimeImmutable|null $to   the ending date of the filter range
 */
final readonly class FetchEmployerRollCountStatisticsFilter
{
    /**
     * @param \DateTimeImmutable|null $from
     * @param \DateTimeImmutable|null $to
     */
    public function __construct(
        public ?\DateTimeImmutable $from = null,
        public ?\DateTimeImmutable $to = null,
    ) {
    }
}
