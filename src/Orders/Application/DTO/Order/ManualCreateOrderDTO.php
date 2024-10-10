<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO\Order;

/**
 * OrderData class represents order data.
 */
final readonly class ManualCreateOrderDTO
{
    /**
     * Constructor for initializing a new instance of MyClass.
     *
     * @param string      $customerName          The name of the customer
     * @param string      $shippingAddress       The shipping address for the customer
     * @param string|null $customerNotes         Additional notes for the customer (nullable)
     * @param string|null $packagingInstructions Packaging instructions for the shipment (nullable)
     */
    public function __construct(
        public string $customerName,
        public string $shippingAddress,
        public ?string $customerNotes = null,
        public ?string $packagingInstructions = null,
    ) {
    }
}
