<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindAnOrder;

use App\Orders\Application\DTO\OrderData;

/**
 * Represents the result of finding an order.
 */
final readonly class FindAnOrderResult
{

    public function __construct(public OrderData $orderData)
    {
    }
}
