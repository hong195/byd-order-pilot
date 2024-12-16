<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindRollHistory;

use App\Shared\Application\Query\Query;

/**
 * Class FindRollHistoryQuery.
 *
 * This class represents a query to find rolls.
 */
final readonly class FindRollHistoryQuery extends Query
{
    /**
     * Constructs a new instance of the class.
     *
     * @param string $rollId The ID of the roll
     *
     * @return void
     */
    public function __construct(public string $rollId)
    {
    }
}
