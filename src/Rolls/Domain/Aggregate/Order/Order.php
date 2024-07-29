<?php

declare(strict_types=1);

namespace App\Rolls\Domain\Aggregate\Order;

use App\Rolls\Domain\Aggregate\Lamination\LaminationType;
use App\Rolls\Domain\Aggregate\Order\ValueObject\Status;
use App\Rolls\Domain\Aggregate\Roll\RollType;
use App\Shared\Domain\Aggregate\Aggregate;
use App\Shared\Domain\Entity\MediaFile;

/**
 * Class Order.
 */
final class Order extends Aggregate
{
    public const CUT_FILE = 'cut_file';

    public const PRINT_FILE = 'print_file';
    /**
     * @phpstan-ignore-next-line
     */
    private ?int $id;

    private Status $status = Status::NEW;

    private readonly \DateTimeInterface $dateAdded;

    private ?MediaFile $cutFile = null;
    private ?MediaFile $printFile = null;

    /**
     * Constructs a new object.
     *
     * @param Priority            $priority       the priority of the product
     * @param ProductType         $productType    the type of the product
     * @param RollType|null       $rollType       the type of the roll (nullable)
     * @param LaminationType|null $laminationType the type of the lamination (nullable)
     * @param ?int                $orderNumber    the order number (nullable)
     */
    public function __construct(
        private Priority $priority,
        private readonly ProductType $productType,
        private ?RollType $rollType = null,
        private ?LaminationType $laminationType = null,
        private ?int $orderNumber = null,
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
     * @param int $orderNumber the new order number
     */
    public function changeOrderNumber(int $orderNumber): void
    {
        $this->orderNumber = $orderNumber;
    }
}
