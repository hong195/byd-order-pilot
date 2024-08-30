<?php

declare(strict_types=1);

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\ValueObject\Customer;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;
use App\Orders\Domain\ValueObject\ShippingAddress;
use App\Orders\Domain\ValueObject\Status;

/**
 * Class OrderFactory.
 *
 * Creates new orders.
 */
final class OrderFactory
{
    private ?Status $status = Status::ASSIGNABLE;

    private ?LaminationType $laminationType = null;

    private ?string $orderNumber = null;

    private ?string $packagingInstructions = null;

    /**
     * Create a new Order instance with the given parameters.
     *
     * @param int         $length   the length of the order
     * @param string|null $filmType the film type of the order (optional)
     *
     * @return Order the created Order instance
     */
    public function make(Customer $customer, ShippingAddress $shippingAddress, int $length, FilmType $filmType): Order
    {
        return new Order(
            customer: $customer,
            shippingAddress: $shippingAddress,
            length: $length,
            filmType: $filmType,
            status: $this->status,
            laminationType: $this->laminationType,
            orderNumber: $this->orderNumber,
            packagingInstructions: $this->packagingInstructions
        );
    }

    /**
     * Set the status of the object.
     *
     * @param Status $status the status object
     */
    public function withStatus(Status $status): void
    {
        $this->status = $status;
    }

    /**
     * Set the lamination type.
     *
     * @param LaminationType $laminationType the lamination type
     */
    public function withLamination(LaminationType $laminationType): void
    {
        $this->laminationType = $laminationType;
    }

    /**
     * Sets the order number for the object.
     *
     * @param string $orderNumber The order number to set
     */
    public function withOrderNumber(string $orderNumber): void
    {
        $this->orderNumber = $orderNumber;
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
