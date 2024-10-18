<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindPartiallyPackedOrders;

use App\Shared\Application\Query\Query;

/**
 * Class FindOrdersWithExtrasQuery.
 *
 * @readonly
 */
final readonly class FindPartiallyPackedOrdersQuery extends Query
{
    public function __construct()
    {
    }
}
