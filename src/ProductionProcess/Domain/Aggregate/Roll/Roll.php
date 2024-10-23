<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate\Roll;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Printer;
use App\ProductionProcess\Domain\Events\RollProcessWasUpdatedEvent;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Aggregate\Aggregate;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Webmozart\Assert\Assert;

/**
 * Class Roll.
 *
 * Represents a roll in the application.
 */
class Roll extends Aggregate
{
    private ?int $id = null;
    private ?int $filmId = null;
    private ?int $glowId = null;
    private \DateTimeImmutable $dateAdded;
    /**
     * @var Collection<PrintedProduct>
     */
    private Collection $printedProducts;
    private ?Printer $printer = null;

    private ?int $employeeId = null;

    private ?self $parentRoll = null;

    /**
     * Constructs a new object with the given name, roll type, and lamination types.
     *
     * @param string $name the name of the object
     *
     * @return void
     */
    public function __construct(private string $name, ?int $filmId = null, private ?Process $process = Process::ORDER_CHECK_IN)
    {
        $this->filmId = $filmId;
        $this->printedProducts = new ArrayCollection([]);
        $this->dateAdded = new \DateTimeImmutable();
    }

    /**
     * Assigns a printer to Roll.
     *
     * @param Printer $printer the printer to be assigned
     */
    public function assignPrinter(Printer $printer): void
    {
        $this->printer = $printer;
    }

    /**
     * Retrieves the printer associated with this object.
     *
     * @return Printer|null the printer associated with this object, or null if none is set
     */
    public function getPrinter(): ?Printer
    {
        return $this->printer;
    }

    /**
     * Returns the id of the entity.
     *
     * @return int the id of the entity
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns the name of the entity.
     *
     * @return string the name of the entity
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Changes the name of the entity.
     *
     * @param string $name the new name for the entity
     *
     * @throws \InvalidArgumentException if name is empty
     */
    public function changeName(string $name): void
    {
        Assert::notEmpty($name, 'Name cannot be empty');
        $this->name = $name;
    }

    /**
     * Returns the date when the entity was added.
     *
     * @return \DateTimeImmutable the date when the entity was added
     */
    public function getDateAdded(): \DateTimeImmutable
    {
        return $this->dateAdded;
    }

    /**
     * Retrieves the status associated with this object.
     *
     * @return Process the status associated with this object
     */
    public function getProcess(): Process
    {
        return $this->process;
    }

    /**
     * Updates the process associated with this object.
     *
     * @param Process $process The new process to be associated with this object
     */
    public function updateProcess(Process $process): void
    {
        $this->process = $process;

        $this->raise(new RollProcessWasUpdatedEvent($this->id));
    }

    /**
     * Retrieves the coil ID associated with this object.
     *
     * @return ?int the coil ID associated with this object
     */
    public function getFilmId(): ?int
    {
        return $this->filmId;
    }

    /**
     * Assigns the given coil ID to this object.
     *
     * @param int $filmId the ID of the coil to be assigned
     */
    public function setFilmId(int $filmId): void
    {
        $this->filmId = $filmId;
    }

    /**
     * Retrieves an array of roll types associated with the printedProducts in this object.
     *
     * @return string[] an array of roll types associated with the printedProducts in this object
     */
    public function getFilmTypes(): array
    {
        return array_values(array_unique($this->printedProducts->map(fn (PrintedProduct $printedProduct) => $printedProduct->getFilmType())->toArray()));
    }

    /**
     * Retrieves the lamination types associated with this object.
     *
     * @return string[] an array of lamination types
     */
    public function getLaminations(): array
    {
        return array_values(array_unique($this->printedProducts->map(fn (PrintedProduct $printedProduct) => $printedProduct->getLaminationType())->toArray()));
    }

    /**
     * Retrieves the employee ID associated with this object.
     *
     * @return ?int the employee ID associated with this object
     */
    public function getEmployeeId(): ?int
    {
        return $this->employeeId;
    }

    /**
     * Assigns the given employee ID to this object.
     *
     * @param ?int $employeeId the ID of the employee to be assigned
     */
    public function setEmployeeId(?int $employeeId): void
    {
        $this->employeeId = $employeeId;
    }

    /**
     * Sets the parent roll for this object.
     *
     * @param Roll $roll the parent roll to set
     */
    public function setParentRoll(Roll $roll): void
    {
        $this->parentRoll = $roll;
    }

    /**
     * Get the parent roll.
     *
     * @return self|null the parent roll if it exists, null otherwise
     */
    public function getParentRoll(): ?self
    {
        return $this->parentRoll;
    }

    /**
     * Clone the object.
     */
    public function __clone(): void
    {
        if ($this->id) {
            $this->id = null;
            $this->printer = null;
            $this->parentRoll = null;
            $this->dateAdded = new \DateTimeImmutable();
            $this->printedProducts = new ArrayCollection([]);
        }
    }

    /**
     * Retrieves the collection of printedProducts associated with this object.
     *
     * @return Collection<PrintedProduct> the collection of printedProducts associated with this object
     */
    public function getPrintedProducts(): Collection
    {
        return $this->printedProducts;
    }

    /**
     * Get the glowId.
     *
     * @return int|null the glowId or null if it is not set
     */
    public function getGlowId(): ?int
    {
        return $this->glowId;
    }

    /**
     * Sets the glowId property.
     *
     * @param int|null $glowId The glowId value. Can be null.
     */
    public function setGlowId(?int $glowId): void
    {
        $this->glowId = $glowId;
    }

    /**
     * Add a printedProduct to the roll.
     *
     * @param PrintedProduct $printedProduct The printedProduct to be added
     */
    public function addPrintedProduct(PrintedProduct $printedProduct): void
    {
        $printedProduct->setRoll($this);
        $this->printedProducts->add($printedProduct);
    }

    /**
     * Get the total length of printedProducts.
     *
     * @return float|int the total length of printedProducts as a float or integer
     */
    public function getPrintedProductsLength(): float|int
    {
        return $this->printedProducts->reduce(fn (float|int $carry, PrintedProduct $printedProduct) => $carry + $printedProduct->getLength(), 0);
    }

    /**
     * Remove all printedProducts.
     */
    public function removePrintedProducts(): void
    {
        $this->printedProducts->clear();
    }

    /**
     * Gets the number of printed products with priority.
     *
     * @return int returns the number of printed products with priority
     */
    public function getPrintedProductsWithPriority(): int
    {
        return $this->printedProducts->filter(fn (PrintedProduct $printedProduct) => true === $printedProduct->hasPriority())->count();
    }

    /**
     * Determines if the process is finished.
     *
     * @return bool returns true if the process is finished, false otherwise
     */
    public function isFinished(): bool
    {
        return $this->process->isFinished();
    }
}
