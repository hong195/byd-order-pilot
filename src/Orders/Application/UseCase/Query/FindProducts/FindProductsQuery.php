<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindProducts;

use App\Shared\Application\Query\Query;

/**
 * Class FindOrdersQuery.
 *
 * @readonly
 */
final readonly class FindProductsQuery extends Query
{
    public function __construct(public ?int $orderId = null)
    {
    }
}
