<?php

/**
 * Represents a filter for date ranges with optional start and end dates.
 */

namespace App\Shared\Domain\Repository;

/**
 *
 */
class DateRangeFilter
{
    /**
     * @param \DateTimeImmutable|null $from
     * @param \DateTimeImmutable|null $to
     */
    public function __construct(public ?\DateTimeImmutable $from = null, public ?\DateTimeImmutable $to = null)
    {
    }
}
