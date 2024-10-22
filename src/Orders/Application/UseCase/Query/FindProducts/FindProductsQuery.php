<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindProducts;

use App\Shared\Application\Query\Query;

/**
 * Class FindOrdersWithExtrasQuery.
 *
 * @readonly
 */
final readonly class FindProductsQuery extends Query
{
    /**
     * Constructor for the Symfony application.
     *
     * @param int|null $orderId    Order ID representing the id of the order
     * @param int[]    $productIds An array of product IDs
     */
    public function __construct(public ?int $orderId = null, public array $productIds = [])
    {
    }
}
