<?php

declare(strict_types=1);

/**
 * Class FindEmployerErrorsQuery.
 *
 * This class represents a query to find employer errors within a specified date range.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FindEmployerErrors;

use App\ProductionProcess\Domain\Repository\DateRangeFilter;
use App\Shared\Application\Query\Query;

/**
 * Class GetPrintedProductsProcessDetailQuery.
 *
 * This class represents a query to find rolls.
 */
final readonly class FindEmployerErrorsQuery extends Query
{
    /**
     * Constructor for ErrorFilter class.
     *
     * @param \DateTimeImmutable|null $from The start date and time
     * @param \DateTimeImmutable|null $to   The end date and time
     */
    public function __construct(public ?\DateTimeImmutable $from = null, public ?\DateTimeImmutable $to = null)
    {
    }

    /**
     * Get the error filter instance based on responsible employee id, noticer id, and process.
     *
     * @return DateRangeFilter the error filter instance created with the provided parameters
     */
    public function getErrorFilter(): DateRangeFilter
    {
        return new DateRangeFilter(
            from: $this->from,
            to: $this->to
        );
    }
}
