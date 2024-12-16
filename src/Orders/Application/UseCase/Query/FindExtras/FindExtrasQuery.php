<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindExtras;

use App\Shared\Application\Query\Query;

/**
 * Class FindARollQuery.
 *
 * This class represents a query to find a roll by its ID.
 */
final readonly class FindExtrasQuery extends Query
{
    /**
     * Class constructor.
     *
     * @param string $orderId the roll ID
     */
    public function __construct(public string $orderId)
    {
    }
}
