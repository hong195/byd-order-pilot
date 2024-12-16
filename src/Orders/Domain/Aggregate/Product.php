<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate;

use App\Shared\Domain\Entity\MediaFile;
use App\Shared\Domain\Service\UlidService;

/**
 * Class Orders.
 */
class Product
{
    public const CUT_FILE = 'cut_file';
    public const PRINT_FILE = 'print_file';
    /**
     * @phpstan-ignore-next-line
     */
    private string $id;
    private ?string $laminationType = null;
    private ?MediaFile $cutFile = null;
    private ?MediaFile $printFile = null;
    private ?Order $order = null;
    private bool $isPacked = false;
    private readonly \DateTimeInterface $dateAdded;

    /**
     * Initializes a new instance of the class.
     *
     * @param string $filmType the film type
     * @param float  $length   the length
     */
    public function __construct(public readonly string $filmType, public readonly float $length)
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
     * Sets the print file for uploading.
     *
     * @param MediaFile $printFile The print file to be uploaded
     */
    public function setPrintFile(MediaFile $printFile): void
    {
        $printFile->setOwnerId($this->getId());
        $printFile->setType(self::PRINT_FILE);
        $printFile->setOwnerType(self::class);

        $this->printFile = $printFile;
    }

    /**
     * Uploads the cut file.
     *
     * @param MediaFile $cutFile The cut file to be uploaded
     */
    public function setCutFile(MediaFile $cutFile): void
    {
        $cutFile->setOwnerId($this->getId());
        $cutFile->setType(self::CUT_FILE);
        $cutFile->setOwnerType(self::class);

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
     * Packs the item.
     */
    public function pack(): void
    {
        $this->isPacked = true;
    }

    /**
     * Unpacks the item, setting isPacked to false.
     */
    public function unPack(): void
    {
        $this->isPacked = false;
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
