<?php

declare(strict_types=1);

namespace App\Rolls\Domain\Factory;

use App\Rolls\Domain\Aggregate\Lamination\LaminationType;
use App\Rolls\Domain\Aggregate\Order\Order;
use App\Rolls\Domain\Aggregate\Order\Priority;
use App\Rolls\Domain\Aggregate\Order\ProductType;
use App\Rolls\Domain\Aggregate\Roll\RollType;

final readonly class OrderFactory
{
    /**
     * @param string          $priority       the priority of the order
     * @param string          $productType    the type of product
     * @param string|null     $laminationType the type of lamination (optional)
     * @param string|null     $rollType       the type of roll (optional)
     * @param string|int|null $orderNumber    the order number (optional)
     */
    public function make(string $priority, string $productType, ?string $laminationType = null, ?string $rollType = null, int|string|null $orderNumber = null): Order
    {
        return new Order(
            priority: Priority::from($priority),
            productType: ProductType::from($productType),
            rollType: $rollType ? RollType::from($rollType) : null,
            laminationType: $laminationType ? LaminationType::from($laminationType) : null,
            orderNumber: $orderNumber,
        );
    }
}
