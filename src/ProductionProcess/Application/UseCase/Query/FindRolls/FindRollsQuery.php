<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindRolls;

use App\Shared\Application\Query\Query;

/**
 * Class FindRollsQuery.
 *
 * This class represents a query to find rolls.
 */
final readonly class FindRollsQuery extends Query
{
    /**
     * Class constructor.
     *
     * @param string      $process        The process value (default: null)
     * @param string|null $filmType       The film type value (default: null)
     * @param string|null $laminationType The lamination type value (default: null)
     */
    public function __construct(public string $process, public ?string $filmType = null, public ?string $laminationType = null)
    {
    }
}
