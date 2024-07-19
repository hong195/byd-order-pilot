<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Query\FindALamination;

use App\Shared\Application\Query\Query;

/**
 * Class FindARollQuery.
 *
 * This class represents a query to find a roll by its ID.
 */
final readonly class FindALaminationQuery extends Query
{
    /**
     * Class constructor.
     *
     * @param int $laminationId the lamination ID
     */
    public function __construct(public int $laminationId)
    {
    }
}
