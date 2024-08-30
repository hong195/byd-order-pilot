<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate;

use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;
use App\Orders\Domain\ValueObject\OrderType;
use App\Orders\Domain\ValueObject\Status;
use App\Shared\Domain\Aggregate\Aggregate;
use App\Shared\Domain\Entity\MediaFile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Orders.
 */
final class Order extends Aggregate
{
    public const CUT_FILE = 'cut_file';

    public const PRINT_FILE = 'print_file';
    /**
     * @phpstan-ignore-next-line
     */
    private ?int $id;

    private ?MediaFile $cutFile = null;
    private ?MediaFile $printFile = null;
    private ?Roll $roll = null;

    private Collection $extras;
    private ?int $sortOrder = 0;
    private readonly \DateTimeInterface $dateAdded;

    /**
     * Class constructor.
     *
     * @param int             $length         The length of the product
     * @param FilmType        $filmType       The roll type of the product
     * @param Status          $status         The status of the product (optional, default: Status::UNASSIGNED)
     * @param bool            $hasPriority    Whether the product has priority (optional, default: false)
     * @param ?LaminationType $laminationType The lamination type of the product (optional, default: null)
     * @param string|null     $orderNumber    The order number of the product (optional, default: null)
     */
    public function __construct(
        private readonly int $length,
        private FilmType $filmType,
        private Status $status = Status::ASSIGNABLE,
        private bool $hasPriority = false,
        private ?LaminationType $laminationType = null,
        private ?string $orderNumber = null,
    ) {
        $this->dateAdded = new \DateTimeImmutable();
        $this->extras = new ArrayCollection([]);
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
     * Returns the order number.
     *
     * @return ?int the order number
     */
    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
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
    public function updateHasPriority(bool $hasPriority): void
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
     * Changes the order number.
     *
     * @param string $orderNumber the new order number
     */
    public function changeOrderNumber(string $orderNumber): void
    {
        $this->orderNumber = $orderNumber;
    }

    /**
     * Returns the length.
     *
     * @return int the length
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * Returns the order number.
     *
     * @return int the order sort order
     */
    public function getSortOrder(): int
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
    }

    /**
     * Adds an Extra to the order.
     *
     * @param Extra $extra The Extra to add
     */
    public function addExtra(Extra $extra): void
    {
        $extra->setOrder($this);
        $this->extras->add($extra);
    }

    /**
     * Returns the collection of extras.
     *
     * @return Collection the collection of extras
     */
    public function getExtras(): Collection
    {
        return $this->extras;
    }

    /**
     * Returns the order type based on the presence of extras.
     *
     * @return OrderType the order type
     */
    public function getOrderType(): OrderType
    {
        if (!$this->extras->isEmpty()) {
            return OrderType::Combined;
        }

        return OrderType::Product;
    }
}
