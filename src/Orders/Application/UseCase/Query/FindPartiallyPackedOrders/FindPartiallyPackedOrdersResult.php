<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindPartiallyPackedOrders;

use App\Orders\Application\DTO\Order\OrderData;

/**
 * Represents the result of finding an order.
 */
final readonly class FindPartiallyPackedOrdersResult
{
    /**
     * Class MyClass.
     *
     * This class represents a MyClass object.
     *
     * @param array<OrderData> $items the items to include in the result
     */
    public function __construct(public array $items = [])
    {
    }
}
