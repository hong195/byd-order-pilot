<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO\Order;

/**
 * OrderData class represents order data.
 */
final readonly class OrderData
{
    /**
     * Constructor for creating a new Symfony application object.
     *
     * @param int                $id                    The unique identifier of the application
     * @param string             $customerName          The name of the customer associated with the application
     * @param string             $type                  The type of the application
     * @param string             $shippingAddress       The shipping address of the application
     * @param string|null        $orderNumber           The order number associated with the application, if available
     * @param \DateTimeInterface $addedAt               The date and time when the application was added
     * @param string|null        $customerNotes         Any additional notes or comments from the customer, if available
     * @param string|null        $packagingInstructions Instructions for packaging the application, if available
     */
    public function __construct(
        public int $id,
        public string $customerName,
        public string $type,
        public string $shippingAddress,
        public ?string $orderNumber,
		public bool $isPacked,
        public \DateTimeInterface $addedAt,
        public ?string $customerNotes = null,
        public ?string $packagingInstructions = null,
    ) {
    }
}
