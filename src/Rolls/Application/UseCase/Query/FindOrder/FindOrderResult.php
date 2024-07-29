<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Query\FindOrder;

use App\Rolls\Application\DTO\OrderData;

/**
 * Represents the result of finding an order.
 */
final readonly class FindOrderResult
{

    public function __construct(public OrderData $orderData)
    {
    }
}
