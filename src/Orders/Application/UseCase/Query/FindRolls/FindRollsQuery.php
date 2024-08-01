<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindRolls;

use App\Shared\Application\Query\Query;

/**
 * Class FindRollsQuery.
 *
 * This class represents a query to find rolls.
 */
final readonly class FindRollsQuery extends Query
{
    /**
     * Class Constructor.
     *
     * @param string      $rollType       The type of roll
     * @param string|null $laminationType The type of lamination (optional)
     */
    public function __construct(public string $rollType, public ?string $laminationType = null)
    {
    }
}
