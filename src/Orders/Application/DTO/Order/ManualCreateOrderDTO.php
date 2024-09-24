<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO\Order;

/**
 * OrderData class represents order data.
 */
final readonly class ManualCreateOrderDTO
{
    /**
     * Constructor for the class.
     *
     * @param string      $customerName          the customer's name
     * @param string|null $customerNotes         Any notes from the customer. (Optional)
     * @param string|null $packagingInstructions The packaging instructions for the order. (Optional)
     */
    public function __construct(
        public string $customerName,
        public ?string $customerNotes = null,
        public ?string $packagingInstructions = null,
    ) {
    }
}
