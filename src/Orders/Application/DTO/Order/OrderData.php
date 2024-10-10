<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO\Order;

/**
 * OrderData class represents order data.
 */
final readonly class OrderData
{
    /**
     * Class constructor.
     *
     * @param int                $id                    the ID of the order
     * @param string             $customerName          the name of the customer
     * @param string             $type                  the type of the order
     * @param string|null        $orderNumber           the order number (optional, nullable)
     * @param \DateTimeInterface $addedAt               the date and time at which the order was added
     * @param string|null        $customerNotes         the additional notes provided by the customer (optional, nullable)
     * @param string|null        $packagingInstructions the packaging instructions for the order (optional, nullable)
     */
    public function __construct(
        public int $id,
        public string $customerName,
        public string $type,
        public string $shippingAddress,
        public ?string $orderNumber,
        public \DateTimeInterface $addedAt,
        public ?string $customerNotes = null,
        public ?string $packagingInstructions = null,
    ) {
    }
}
