<?php

declare(strict_types=1);

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;
use App\Orders\Domain\ValueObject\Status;

/**
 * Class OrderFactory.
 *
 * Creates new orders.
 */
final readonly class OrderFactory
{
    /**
     * Create a new Order instance with the given parameters.
     *
     * @param int         $length                the length of the order
     * @param string|null $laminationType        the lamination type of the order (optional)
     * @param string|null $filmType              the film type of the order (optional)
     * @param string|null $status                the status of the order (optional)
     * @param bool        $hasPriority           whether the order has priority (default: false)
     * @param string|null $orderNumber           the order number (optional)
     * @param string|null $customerNotes         additional notes from the customer (optional)
     * @param string|null $packagingInstructions packaging instructions for the order (optional)
     *
     * @return Order the created Order instance
     */
    public function make(string $customerName, int $length, ?string $laminationType = null, ?string $filmType = null,
        ?string $status = null, bool $hasPriority = false, ?string $orderNumber = null,
        ?string $customerNotes = null, ?string $packagingInstructions = null): Order
    {
        return new Order(
            length: $length,
            filmType: $filmType ? FilmType::from($filmType) : null,
            customerName: $customerName,
            status: $status ? Status::from($status) : Status::ASSIGNABLE,
            hasPriority: $hasPriority,
            laminationType: $laminationType ? LaminationType::from($laminationType) : null,
            orderNumber: $orderNumber,
            customerNotes: $customerNotes,
            packagingInstructions: $packagingInstructions
        );
    }
}
