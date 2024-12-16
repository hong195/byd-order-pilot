<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindARoll;

use App\Shared\Application\Query\Query;

/**
 * Class FindARollQuery.
 *
 * This class represents a query to find a roll by its ID.
 */
final readonly class FindARollQuery extends Query
{
    /**
     * Class constructor.
     *
     * @param string $rollId the roll ID
     */
    public function __construct(public string $rollId)
    {
    }
}
