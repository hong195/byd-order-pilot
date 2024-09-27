<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate;
/**
 * Class Printer.
 *
 * Represents a printer.
 */
class Printer
{
    /**
     * @phpstan-ignore-next-line
     */
    private int $id;

    private ?string $color = null;

    private \DateTimeImmutable $dateAdded;

    private bool $isAvailable = true;

    /**
     * Printer constructor.
     *
     * @param string           $name            the name of the printer
     * @param string[]       $filmTypes       an array of roll types
     * @param string[] $laminationTypes an array of lamination types
     */
    public function __construct(private readonly string $name, private array $filmTypes = [], private array $laminationTypes = [])
    {
        $this->dateAdded = new \DateTimeImmutable();
    }

    /**
     * Get the id property.
     *
     * @return int the id property
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the name property.
     *
     * @return string the name property
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the color of the object.
     *
     * @param string $color the color value to set
     */
    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    /**
     * Returns the color of the object.
     *
     * @return string|null the color of the object or null if no color is set
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * Returns the date when the item was added.
     *
     * @return \DateTimeImmutable the date when the item was added
     */
    public function getDateAdded(): \DateTimeImmutable
    {
        return $this->dateAdded;
    }

    /**
     * Returns the available roll types.
     *
     * @return string[] the array of roll types
     */
    public function getFilmTypes(): array
    {
        return $this->filmTypes;
    }

    /**
     * Returns the available lamination types.
     *
     * @return string[] the array of lamination types
     */
    public function getLaminationTypes(): array
    {
        return $this->laminationTypes;
    }

    /**
     * Checks if the object is available.
     *
     * @return bool true if the object is available, false otherwise
     */
    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    /**
     * Changes the availability of the object.
     *
     * @param bool $isAvailable the availability value to set
     */
    public function changeAvailability(bool $isAvailable): void
    {
        $this->isAvailable = $isAvailable;
    }
}
