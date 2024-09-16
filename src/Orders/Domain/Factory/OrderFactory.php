<?php

declare(strict_types=1);

namespace App\Orders\Domain\Factory;

use App\Orders\Domain\Aggregate\Customer;
use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;
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
     * Creates a new Order for a customer.
     *
     * @param Customer $customer The customer for the order
     * @param int|float      $length   The length of the film
     * @param FilmType $filmType The type of film
     *
     * @return Order The created order
     */
    public function make(Customer $customer, int|float $length, FilmType $filmType): Order
    {
        $order = new Order(
            customer: $customer,
            filmType: $filmType,
            length: $length
        );

        if ($this->orderNumber) {
            $order->changeOrderNumber($this->orderNumber);
        }

        if ($this->packagingInstructions) {
            $order->setPackagingInstructions($this->packagingInstructions);
        }

        if ($this->status) {
            $order->changeStatus($this->status);
        }

        if ($this->laminationType) {
            $order->setLaminationType($this->laminationType);
        }

        return $order;
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
