<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate;

class Extra
{
    private ?int $id = null;

    private bool $isPacked = false;

    private ?Order $order = null;

    /**
     * Constructs a new instance of the class.
     *
     * @param string $name        the value of the name (readonly property)
     * @param string $orderNumber the value of the order number (readonly property)
     * @param int    $count       the value of the count (private property with a default value of 0)
     */
    public function __construct(public readonly string $name, public readonly string $orderNumber, private int $count = 0)
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
    public function isPacked(): bool
    {
        return $this->isPacked;
    }

    /**
     * Set the isSent property.
     *
     * @param bool $isPacked the value to set for the isSent property
     */
    public function setIsPacked(bool $isPacked): void
    {
        $this->isPacked = $isPacked;
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

    /**
     * Returns the current count.
     *
     * @return int the current count
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Sets the count.
     *
     * @param int $count the value of the count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }
}
