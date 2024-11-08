<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate\Printer;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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

    private \DateTimeImmutable $dateAdded;

    private bool $isAvailable = true;

    /**
     * @var Collection<Condition>
     */
    private Collection $conditions;

    /**
     * Constructor for initializing a new instance of the class.
     *
     * @param string $name      the name of the instance
     * @param bool   $isDefault A boolean flag indicating if the instance is default or not. Default is false.
     */
    public function __construct(public readonly string $name, public readonly bool $isDefault = false)
    {
        $this->conditions = new ArrayCollection([]);
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
     * Returns the date when the item was added.
     *
     * @return \DateTimeImmutable the date when the item was added
     */
    public function getDateAdded(): \DateTimeImmutable
    {
        return $this->dateAdded;
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

    /**
     * Check if the printer can print the given PrintedProduct based on its conditions.
     *
     * @param PrintedProduct $printedProduct The PrintedProduct to check if it can be printed
     *
     * @return bool True if the printer can print the PrintedProduct, false otherwise
     */
    public function canPrintProduct(PrintedProduct $printedProduct): bool
    {
        foreach ($this->conditions as $condition) {
            if ($condition->isSatisfiedBy(
                filmType: $printedProduct->filmType,
                laminationRequired: (bool) $printedProduct->getLaminationType(),
                laminationType: $printedProduct->getLaminationType())) {
                return true;
            }
        }

        return false;
    }
}
