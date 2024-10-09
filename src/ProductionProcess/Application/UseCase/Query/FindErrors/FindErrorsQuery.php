<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindErrors;

use App\ProductionProcess\Domain\Repository\ErrorFilter;
use App\Shared\Application\Query\Query;

/**
 * Class FindRollsQuery.
 *
 * This class represents a query to find rolls.
 */
final readonly class FindErrorsQuery extends Query
{
    /**
     * Constructor for ErrorFilter class.
     *
     * @param string|null $process               The process associated with the error filter
     * @param int|null    $responsibleEmployeeId The ID of the responsible employee associated with the error filter
     * @param int|null    $noticerId             The ID of the noticer associated with the error filter
     */
    public function __construct(public ?string $process = null, public ?int $responsibleEmployeeId = null, public ?int $noticerId = null)
    {
    }

    /**
     * Get the error filter instance based on responsible employee id, noticer id, and process.
     *
     * @return ErrorFilter the error filter instance created with the provided parameters
     */
    public function getErrorFilter(): ErrorFilter
    {
        return new ErrorFilter(
            responsibleEmployeeId: $this->responsibleEmployeeId,
            noticerId: $this->noticerId,
            process: $this->process,
        );
    }
}
