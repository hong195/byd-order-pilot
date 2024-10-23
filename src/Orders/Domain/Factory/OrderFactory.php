<?php

declare(strict_types=1);

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Customer;
use App\Orders\Domain\Aggregate\Order;

/**
 * Class OrderFactory.
 *
 * Creates new orders.
 */
final class OrderFactory
{
    private ?string $orderNumber = null;

    private ?string $packagingInstructions = null;

    /**
     * Creates a new Order for a customer.
     *
     * @param Customer $customer The customer for the order
     *
     * @return Order The created order
     */
    public function make(Customer $customer, string $shippingAddress, string $orderNumber = null): Order
    {
        $order = new Order(
            customer: $customer,
			shippingAddress: $shippingAddress
        );

        if ($orderNumber) {
            $order->changeOrderNumber($orderNumber);
        }

        if ($this->packagingInstructions) {
            $order->setPackagingInstructions($this->packagingInstructions);
        }

        return $order;
    }
    /**
     * Sets the packaging instructions.
     *
     * @param string $packagingInstructions the packaging instructions to set
     */
    public function withPackagingInstructions(string $packagingInstructions): void
    {
        $this->packagingInstructions = $packagingInstructions;
    }
}
