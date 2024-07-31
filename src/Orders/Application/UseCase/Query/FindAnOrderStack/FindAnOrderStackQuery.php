<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindAnOrderStack;

use App\Shared\Application\Query\Query;

/**
 * FindAnOrderStackQuery is a query class that represents a query to find an order stack by its ID.
 */
final readonly class FindAnOrderStackQuery extends Query
{
    /**
     * Class constructor.
     *
     * @param int $orderStackId the orderStack ID
     */
    public function __construct(public int $orderStackId)
    {
    }
}
