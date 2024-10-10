<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\ManuallyAddOrder;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to manually add an order.
 */
readonly class ManuallyAddOrderCommand implements CommandInterface
{
    /**
     * Constructor for initializing customer information and shipping details.
     *
     * @param string      $customerName          The name of the customer
     * @param string      $shippingAddress       The shipping address of the customer
     * @param string|null $customerNotes         Additional notes provided by the customer (optional)
     * @param string|null $packagingInstructions Special packaging instructions (optional)
     */
    public function __construct(
        public string $customerName,
        public string $shippingAddress,
        public ?string $customerNotes = null,
        public ?string $packagingInstructions = null,
    ) {
    }
}
