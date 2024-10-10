<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate;

use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;
use App\Shared\Domain\Entity\MediaFile;

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
    private ?int $id;
    private ?LaminationType $laminationType = null;
    private ?MediaFile $cutFile = null;
    private ?MediaFile $printFile = null;
    private ?Order $order = null;
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
