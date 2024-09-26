<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindAProduct;

use App\Shared\Application\Query\Query;

/**
 * Class FindOrdersQuery.
 *
 * @readonly
 */
final readonly class FindAProductQuery extends Query
{
    public function __construct(public int $productId)
    {
    }
}
