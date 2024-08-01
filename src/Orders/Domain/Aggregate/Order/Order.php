<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate\Order;

use App\Orders\Domain\Aggregate\Lamination\LaminationType;
use App\Orders\Domain\Aggregate\Order\ValueObject\Status;
use App\Orders\Domain\Aggregate\Roll\RollType;
use App\Shared\Domain\Aggregate\Aggregate;
use App\Shared\Domain\Entity\MediaFile;

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

    private Status $status = Status::NEW;

    private readonly \DateTimeInterface $dateAdded;

    private ?MediaFile $cutFile = null;
    private ?MediaFile $printFile = null;

    /**
     * Class constructor.
     *
     * @param int                 $length         The length of the product
     * @param ProductType         $productType    The type of product
     * @param RollType            $rollType       The roll type
     * @param bool                $hasPriority    Whether the product has priority
     * @param LaminationType|null $laminationType The lamination type (optional)
     * @param int|null            $orderNumber    The order number (optional)
     *
     * @return void
     */
    public function __construct(
        private readonly int $length,
        private readonly ProductType $productType,
        private RollType $rollType,
        private bool $hasPriority = false,
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

    public function getLength(): int
    {
        return $this->length;
    }
}
