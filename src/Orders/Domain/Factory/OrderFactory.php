<?php

declare(strict_types=1);

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Lamination\LaminationType;
use App\Orders\Domain\Aggregate\Order\Order;
use App\Orders\Domain\Aggregate\Order\ProductType;
use App\Orders\Domain\Aggregate\Roll\RollType;

/**
 * Class OrderFactory.
 *
 * Creates new orders.
 */
final readonly class OrderFactory
{
    /**
     * Creates a new order.
     *
     * @param int             $length         the length of the order
     * @param string          $productType    the type of product for the order
     * @param string|null     $laminationType the type of lamination for the order, or null if no lamination is needed
     * @param string|null     $rollType       the type of roll for the order, or null if no roll is needed
     * @param bool            $hasPriority    true if the order has priority, false otherwise
     * @param int|string|null $orderNumber    the order number, or null if no order number is provided
     *
     * @return Order the newly created order
     */
    public function make(int $length, string $productType, ?string $laminationType = null, ?string $rollType = null, $hasPriority = false, int|string|null $orderNumber = null): Order
    {
        return new Order(
            length: $length,
            productType: ProductType::from($productType),
            rollType: $rollType ? RollType::from($rollType) : null,
            hasPriority: $hasPriority,
            laminationType: $laminationType ? LaminationType::from($laminationType) : null,
            orderNumber: $orderNumber,
        );
    }
}
