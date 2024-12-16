<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Events\PrintedProductReprintedEvent;
use App\Shared\Domain\Aggregate\Aggregate;
use App\Shared\Domain\Entity\MediaFile;
use App\Shared\Domain\Service\UlidService;

/**
 * Class Job.
 */
class PrintedProduct extends Aggregate
{
    public const PRODUCT_PHOTO = 'product_photo';

    private string $id;
    private ?string $laminationType = null;
    private ?Roll $roll = null;
    private ?int $sortOrder = null;
    private bool $hasPriority = false;
    private bool $isReprint = false;
    private readonly \DateTimeInterface $dateAdded;
    private ?MediaFile $photo = null;

    /**
     * Constructs a new instance of the class.
     *
     * @param int       $relatedProductId the product id
     * @param string    $orderNumber      the order number
     * @param string    $filmType         the film type
     * @param int|float $length           the length
     */
    public function __construct(public readonly int $relatedProductId, public readonly string $orderNumber, public readonly string $filmType, public readonly int|float $length)
    {
        $this->id = UlidService::generate();
        $this->dateAdded = new \DateTimeImmutable();
    }

    /**
     * Returns the ID of the object.
     *
     * @return string the ID of the object
     */
    public function getId(): string
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
     * Unassigns the roll.
     */
    public function unassign(): void
    {
        $this->roll = null;
        $this->sortOrder = null;
    }

    /**
     * Returns the roll type.
     *
     * @return string the roll type
     */
    public function getFilmType(): string
    {
        return $this->filmType;
    }

    /**
     * Returns the lamination type.
     *
     * @return ?string the lamination type
     */
    public function getLaminationType(): ?string
    {
        return $this->laminationType;
    }

    /**
     * Sets the lamination type.
     *
     * @param string $laminationType the lamination type to set
     */
    public function setLaminationType(string $laminationType): void
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
     * Checks if the item is a reprint.
     *
     * @return bool true if the item is a reprint, false otherwise
     */
    public function isReprint(): bool
    {
        return $this->isReprint;
    }

    /**
     * Reprints the document.
     *
     * Sets the status to ASSIGNABLE, sets hasPriority to true and roll to null.
     */
    public function reprint(): void
    {
        $this->hasPriority = true;
        $this->isReprint = true;
        $this->sortOrder = null;

        $this->raise(new PrintedProductReprintedEvent(printedProductId: $this->id));
    }

    public function getPhoto(): ?MediaFile
    {
        return $this->photo;
    }

    /**
     * Uploads the product photo.
     *
     * @param MediaFile $photo The product photo to be uploaded
     */
    public function setPhoto(MediaFile $photo): void
    {
        $photo->setOwnerId($this->getId());
        $photo->setType(self::PRODUCT_PHOTO);
        $photo->setOwnerType(self::class);

        $this->photo = $photo;
    }
}
