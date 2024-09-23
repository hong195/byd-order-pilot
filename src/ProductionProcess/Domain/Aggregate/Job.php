<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\ValueObject\FilmType;
use App\ProductionProcess\Domain\ValueObject\LaminationType;
use App\ProductionProcess\Domain\ValueObject\Status;

/**
 * Class Job.
 */
class Job
{
    /**
     * @phpstan-ignore-next-line
     */
    private ?int $id;
    private Status $status;
    private ?LaminationType $laminationType = null;
    private ?Roll $roll = null;
    private ?int $sortOrder = null;
    private bool $hasPriority = false;
    private readonly \DateTimeInterface $dateAdded;

    /**
     * Constructs a new instance of the class.
     *
     * @param int       $productId   the product ID
     * @param string    $orderNumber the order number
     * @param FilmType  $filmType    the film type
     * @param int|float $length      the length of the film in minutes or seconds
     */
    public function __construct(public readonly int $productId, public readonly string $orderNumber, public readonly FilmType $filmType, public readonly int|float $length)
    {
        $this->dateAdded = new \DateTimeImmutable();
    }

    /**
     * Returns the ID of the object.
     *
     * @return int the ID of the object
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns the date when the entry was added.
     *
     * @return \DateTimeInterface the date when the entry was added
     */
    public function getDateAdded(): \DateTimeInterface
    {
        return $this->dateAdded;
    }

    /**
     * Updates the priority status.
     *
     * @param bool $hasPriority the priority status
     */
    public function updatePriority(bool $hasPriority): void
    {
        $this->hasPriority = $hasPriority;
    }

    /**
     * Returns the priority.
     *
     * @return bool the priority
     */
    public function hasPriority(): bool
    {
        return $this->hasPriority;
    }

    /**
     * Returns the status.
     *
     * @return Status the status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * Changes the status of the object.
     *
     * @param Status $status the new status
     */
    public function changeStatus(Status $status): void
    {
        $this->status = $status;
    }

    /**
     * Returns the roll type.
     *
     * @return FilmType the roll type
     */
    public function getFilmType(): FilmType
    {
        return $this->filmType;
    }

    /**
     * Returns the lamination type.
     *
     * @return ?LaminationType the lamination type
     */
    public function getLaminationType(): ?LaminationType
    {
        return $this->laminationType;
    }

    /**
     * Sets the lamination type.
     *
     * @param LaminationType $laminationType the lamination type to set
     */
    public function setLaminationType(LaminationType $laminationType): void
    {
        $this->laminationType = $laminationType;
    }

    /**
     * Returns the length.
     *
     * @return int|float the length
     */
    public function getLength(): float|int
    {
        return $this->length;
    }

    /**
     * Returns the order number.
     *
     * @return ?int the order sort order
     */
    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    /**
     * Changes the sort order.
     *
     * @param ?int $sortOrder the new sort order
     */
    public function changeSortOrder(?int $sortOrder = null): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * Returns the roll object.
     *
     * @return ?Roll the roll object
     */
    public function getRoll(): ?Roll
    {
        return $this->roll;
    }

    /**
     * Sets the roll.
     *
     * @param Roll $roll the roll
     */
    public function setRoll(Roll $roll): void
    {
        $this->roll = $roll;
    }

    /**
     * Removes the roll.
     */
    public function removeRoll(): void
    {
        $this->roll = null;
    }

    /**
     * Reprints the document.
     *
     * Sets the status to ASSIGNABLE, sets hasPriority to true and roll to null.
     */
    public function reprint(): void
    {
        $this->status = Status::ASSIGNABLE;
        $this->hasPriority = true;
        $this->roll = null;
        $this->sortOrder = null;
    }
}
