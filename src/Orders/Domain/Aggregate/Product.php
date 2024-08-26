<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate;

use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;

final class Product
{
    private ?int $id = null;

    private bool $isPacked = false;

    private bool $isSent = false;

    private bool $hasPriority = false;

    private readonly \DateTimeInterface $dateAdded;

    private ?Order $order = null;

    /**
     * Constructs a new instance of the class.
     *
     * @param FilmType       $filmType       The film type associated with the instance
     * @param LaminationType $laminationType The lamination type associated with the instance
     */
    public function __construct(public FilmType $filmType, public LaminationType $laminationType)
    {
        $this->dateAdded = new \DateTimeImmutable();
    }

    /**
     * Returns the ID of the object.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Checks if the item is packed.
     *
     * @return bool the value indicating if the item is packed
     */
    public function isPacked(): bool
    {
        return $this->isPacked;
    }

    /**
     * Sets the value indicating if the product is packed.
     *
     * @param bool $isPacked the value indicating if the product is packed
     */
    public function setIsPacked(bool $isPacked): void
    {
        $this->isPacked = $isPacked;
    }

    /**
     * Checks if the item is sent.
     *
     * @return bool the value indicating if the item is sent
     */
    public function isSent(): bool
    {
        return $this->isSent;
    }

    /**
     * Set the value of isSend property.
     *
     * @param bool $isSent the value indicating if the product is sent
     */
    public function setIsSent(bool $isSent): void
    {
        $this->isSent = $isSent;
    }

    /**
     * Checks if the item has priority.
     *
     * @return bool the value indicating if the item has priority
     */
    public function isHasPriority(): bool
    {
        return $this->hasPriority;
    }

    /**
     * Set the priority of the object.
     *
     * @param bool $hasPriority the priority value
     */
    public function setHasPriority(bool $hasPriority): void
    {
        $this->hasPriority = $hasPriority;
    }

    /**
     * Get the date added of the object.
     *
     * @return \DateTimeInterface the date added of the object
     */
    public function getDateAdded(): \DateTimeInterface
    {
        return $this->dateAdded;
    }

    /**
     * Sets the order for this item.
     *
     * @param Order $order the order object to be set
     */
    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }
}
