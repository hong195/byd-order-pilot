<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindPrintedProducts;

use App\Shared\Application\Query\Query;

/**
 * Class FindPrintedProductsQuery.
 *
 * Represents a query to find printed products.
 */
final readonly class FindPrintedProductsQuery extends Query
{
    /**
     * Class constructor.
     *
     * @param bool|null $unassigned [optional] Whether or not the value is unassigned. Defaults to null.
     * @param int|null  $rollId     [optional] The roll ID. Defaults to null.
     */
    public function __construct(public ?bool $unassigned = null, public ?string $rollId = null)
    {
    }
}
