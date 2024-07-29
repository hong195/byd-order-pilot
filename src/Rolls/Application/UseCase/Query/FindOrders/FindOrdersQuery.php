<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Query\FindOrders;

use App\Shared\Application\Query\Query;

/**
 * Class FindOrdersQuery
 *
 * @final
 * @readonly
 */
final readonly class FindOrdersQuery extends Query
{
    public function __construct(string $status = null)
    {
    }
}
