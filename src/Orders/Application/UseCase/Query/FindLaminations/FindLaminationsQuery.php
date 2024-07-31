<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindLaminations;

use App\Shared\Application\Query\Query;

/**
 * Class FindRollsQuery.
 *
 * This class represents a query to find rolls.
 */
final readonly class FindLaminationsQuery extends Query
{
    /**
     * @param int $page The page number to be set. Defaults to 1.
     */
    public function __construct(public int $page = 1)
    {
    }
}
