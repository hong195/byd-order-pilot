<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate;

use App\Orders\Domain\Aggregate\Roll\Roll;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;
use App\Orders\Domain\ValueObject\Status;
use App\Shared\Domain\Aggregate\Aggregate;
use App\Shared\Domain\Entity\MediaFile;

/**
 * Class Orders.
 */
final class Product extends Aggregate
{
    public const CUT_FILE = 'cut_file';
    public const PRINT_FILE = 'print_file';
    /**
     * @phpstan-ignore-next-line
     */
    private ?int $id;
    private Status $status = Status::UNASSIGNED;
    private ?LaminationType $laminationType = null;
    private ?MediaFile $cutFile = null;
    private ?MediaFile $printFile = null;
    private ?Roll $roll = null;
    private ?Order $order = null;
    private ?int $sortOrder = 0;
    private bool $hasPriority = false;
    private bool $isPacked = false;
    private readonly \DateTimeInterface $dateAdded;

    /**
     * Initializes a new instance of the class.
     *
     * @param FilmType  $filmType the film type
     * @param int|float $length   the length
     */
    public function __construct(public FilmType $filmType, public readonly int|float $length)
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
     * Sets the print file for uploading.
     *
     * @param MediaFile $printFile The print file to be uploaded
     */
    public function setPrintFile(MediaFile $printFile): void
    {
        $this->printFile = $printFile;
    }

    /**
     * Uploads the cut file.
     *
     * @param MediaFile $cutFile The cut file to be uploaded
     */
    public function setCutFile(MediaFile $cutFile): void
    {
        $this->cutFile = $cutFile;
    }

    /**
     * Returns the print file.
     *
     * @return ?MediaFile the print file
     */
    public function getPrintFile(): ?MediaFile
    {
        return $this->printFile;
    }

    /**
     * Returns the cut file.
     *
     * @return ?MediaFile the cut file
     */
    public function getCutFile(): ?MediaFile
    {
        return $this->cutFile;
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
     * Sets the roll type.
     *
     * @param FilmType $filmType the roll type to set
     */
    public function setFilmType(FilmType $filmType): void
    {
        $this->filmType = $filmType;
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

    /**
     * Sets whether the item is packed or not.
     *
     * @param bool $isPack whether the item is packed or not
     */
    public function setIsPack(bool $isPack): void
    {
        $this->isPacked = $isPack;
    }

    /**
     * Returns whether the item is packed or not.
     *
     * @return bool true if the item is packed, false otherwise
     */
    public function isPacked(): bool
    {
        return $this->isPacked;
    }

    /**
     * Returns the order object.
     *
     * @return ?Order the order object
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * Sets the order for this object.
     *
     * @param Order $order the order to set
     */
    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    /**
     * Gets the order number.
     *
     * @return string the order number
     */
    public function getOrderNumber(): string
    {
        return $this->order->getOrderNumber();
    }
}
