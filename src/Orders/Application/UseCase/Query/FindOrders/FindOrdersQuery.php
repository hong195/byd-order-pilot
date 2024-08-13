<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindOrders;

use App\Shared\Application\Query\Query;

/**
 * Class FindOrdersQuery.
 *
 * @readonly
 */
final readonly class FindOrdersQuery extends Query
{
    public function __construct(public ?int $rollId = null, public ?string $status = null)
    {
    }
}
