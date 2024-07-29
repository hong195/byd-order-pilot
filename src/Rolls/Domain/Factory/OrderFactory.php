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
	 *
	 */
    public function make(string $priority, int $length, string $productType, ?string $laminationType = null, ?string $rollType = null, int|string|null $orderNumber = null): Order
    {
        return new Order(
            priority: Priority::from($priority),
			length: $length,
            productType: ProductType::from($productType),
            rollType: $rollType ? RollType::from($rollType) : null,
            laminationType: $laminationType ? LaminationType::from($laminationType) : null,
            orderNumber: $orderNumber,
        );
    }
}
