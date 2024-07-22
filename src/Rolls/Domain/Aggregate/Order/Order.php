<?php

declare(strict_types=1);

namespace App\Rolls\Domain\Aggregate\Order;

use App\Rolls\Domain\Aggregate\Lamination\LaminationType;
use App\Rolls\Domain\Aggregate\Roll\RollType;
use App\Shared\Domain\Aggregate\Aggregate;
use App\Shared\Domain\Entity\MediaFile;

/**
 * Class Order.
 */
final class Order extends Aggregate
{
    /**
     * @phpstan-ignore-next-line
     */
    private ?int $id;

    private Status $status = Status::NEW;

    private readonly \DateTimeInterface $dateAdded;

    private ?MediaFile $cutFile = null;

    private ?MediaFile $printFile = null;

    private ?\DateTimeImmutable $updatedAt = null;

    private ?RollType $rollType = null;

    private ?LaminationType $laminationType = null;

    /**
     * Constructs a new instance of the class.
     *
     * @param Priority    $priority    the priority of the product
     * @param ProductType $productType the type of the product
     * @param int|null    $orderNumber the order number (optional)
     */
    public function __construct(
        private Priority $priority,
        private readonly ProductType $productType,
        private readonly ?int $orderNumber = null
    ) {
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
     * Returns the order number.
     *
     * @return ?int the order number
     */
    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    /**
     * Get the product type.
     *
     * @return ProductType the product type value
     */
    public function getProductType(): ProductType
    {
        return $this->productType;
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
     * Returns the priority.
     *
     * @return Priority the priority
     */
    public function getPriority(): Priority
    {
        return $this->priority;
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
    public function uploadPrintFile(MediaFile $printFile): void
    {
        $this->printFile = $printFile;
    }

    /**
     * Uploads the cut file.
     *
     * @param MediaFile $cutFile The cut file to be uploaded
     */
    public function uploadCutFile(MediaFile $cutFile): void
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
     * Returns the updated date and time.
     *
     * @return \DateTimeImmutable the updated date and time
     */
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Changes the priority of the object.
     *
     * @param Priority $priority the new priority
     */
    public function changePriority(Priority $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * Returns the roll type.
     *
     * @return RollType the roll type
     */
    public function getRollType(): RollType
    {
        return $this->rollType;
    }

    /**
     * Sets the roll type.
     *
     * @param RollType $rollType the roll type to set
     */
    public function setRollType(RollType $rollType): void
    {
        $this->rollType = $rollType;
    }

    /**
     * Returns the lamination type.
     *
     * @return LaminationType the lamination type
     */
    public function getLaminationType(): LaminationType
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
}
