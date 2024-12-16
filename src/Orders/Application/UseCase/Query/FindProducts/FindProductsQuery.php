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
     * @param string|null $orderId    Order ID representing the id of the order
     * @param string[]    $productIds An array of product IDs
     */
    public function __construct(public ?string $orderId = null, public array $productIds = [])
    {
    }
}
