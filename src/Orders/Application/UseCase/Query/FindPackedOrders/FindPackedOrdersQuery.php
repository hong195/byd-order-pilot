<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindPackedOrders;

use App\Shared\Application\Query\Query;

/**
 * Class FindOrdersWithExtrasQuery.
 *
 * @readonly
 */
final readonly class FindPackedOrdersQuery extends Query
{
    public function __construct()
    {
    }
}
