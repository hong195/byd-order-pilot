<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate\Printer;

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
     * Printer constructor.
     *
     * @param string $name the name of the printer
     */
    public function __construct(private readonly string $name, public readonly bool $default = false)
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
     * Get the name property.
     *
     * @return string the name property
     */
    public function getName(): string
    {
        return $this->name;
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
     * Get the conditions of the object.
     *
     * @return Collection<Condition> The conditions of the object
     */
    public function getConditions(): Collection
    {
        return $this->conditions;
    }
}
