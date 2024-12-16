<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate\Printer;

use App\Shared\Domain\Service\UlidService;
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
    private string $id;

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
		$this->id = UlidService::generate();
        $this->conditions = new ArrayCollection([]);
        $this->dateAdded = new \DateTimeImmutable();
    }

    /**
     * Get the id property.
     *
     * @return string the id property
     */
    public function getId(): string
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
}
