<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate;

class Extra
{
    private ?int $id = null;

    private bool $isChecked = false;

    private ?Order $order = null;

    public function __construct(public readonly string $name, public readonly string $orderNumber)
    {
    }

    /**
     * Returns the id of the entity.
     *
     * @return int|null returns the id if it exists, otherwise null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns whether or not the message has been sent.
     *
     * @return bool returns true if the message has been sent, otherwise false
     */
    public function checked(): bool
    {
        return $this->isChecked;
    }

    /**
     * Set the isSent property.
     *
     * @param bool $isChecked the value to set for the isSent property
     */
    public function setIsChecked(bool $isChecked): void
    {
        $this->isChecked = $isChecked;
    }

    /**
     * Returns the Roll object associated with this instance.
     *
     * @return Order|null returns the Roll object if there is one associated with this instance, otherwise null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * Sets the roll associated with this instance.
     *
     * @param Order|null $order The roll to associate with this instance, or null to disassociate any existing roll
     */
    public function setOrder(?Order $order): void
    {
        $this->order = $order;
    }
}
