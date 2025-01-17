<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate\Roll;

use App\ProductionProcess\Domain\Aggregate\AggregateRoot;
use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Printer\Printer;
use App\ProductionProcess\Domain\Events\RollProcessWasUpdatedEvent;
use App\ProductionProcess\Domain\Events\RollWasSentToCutCheckInEvent;
use App\ProductionProcess\Domain\Events\RollWasSentToGlowCheckInEvent;
use App\ProductionProcess\Domain\Events\RollWasSentToPrintCheckInEvent;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Service\UlidService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Roll.
 *
 * Represents a roll in the application.
 */
class Roll extends AggregateRoot
{
    private string $id;
    private ?string $filmId = null;
    private ?int $glowId = null;
    private \DateTimeImmutable $dateAdded;
    /**
     * @var Collection<PrintedProduct>
     */
    private Collection $printedProducts;
    private ?Printer $printer = null;

    private ?string $employeeId = null;

    private ?self $parentRoll = null;

    public bool $isLocked = false;

    /**
     * Constructs a new object with the given name, roll type, and lamination types.
     *
     * @param string $name the name of the object
     *
     * @return void
     */
    public function __construct(private string $name, ?string $filmId = null, private ?Process $process = Process::ORDER_CHECK_IN)
    {
        $this->id = UlidService::generate();
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
     * @return string the id of the entity
     */
    public function getId(): string
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
        $oldProcess = $this->process;

        $this->process = $process;

        $this->raise(new RollProcessWasUpdatedEvent(rollId: $this->id, process: $oldProcess->value));
    }

    /**
     * Retrieves the coil ID associated with this object.
     *
     * @return ?string the coil ID associated with this object
     */
    public function getFilmId(): ?string
    {
        return $this->filmId;
    }

    /**
     * Assigns the given coil ID to this object.
     *
     * @param string $filmId the ID of the coil to be assigned
     */
    public function setFilmId(string $filmId): void
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
     * @return ?string the employee ID associated with this object
     */
    public function getEmployeeId(): ?string
    {
        return $this->employeeId;
    }

    /**
     * Assigns the given employee ID to this object.
     *
     * @param ?string $employeeId the ID of the employee to be assigned
     */
    public function setEmployeeId(?string $employeeId): void
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
            $this->id = UlidService::generate();
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
     * Adds printed products to the entity.
     *
     * @param iterable<PrintedProduct> $printedProducts An iterable collection of printed products to add
     */
    public function addPrintedProducts(iterable $printedProducts): void
    {
        foreach ($printedProducts as $printedProduct) {
            $this->addPrintedProduct($printedProduct);
        }
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
        $this->printedProducts->map(fn (PrintedProduct $printedProduct) => $printedProduct->unassign());

        $this->printedProducts->clear();
    }

    public function reprintProduct(PrintedProduct $product): void
    {
        $this->removePrintedProduct($product);
        $product->reprint();
    }

    public function removePrintedProduct(PrintedProduct $product): void
    {
        foreach ($this->printedProducts as $printedProduct) {
            if ($printedProduct->getId() === $product->getId()) {
                $printedProduct->unassign();
                $this->printedProducts->removeElement($printedProduct);

                return;
            }
        }

        throw new \InvalidArgumentException('Product not found');
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

    /**
     * Locks the resource.
     */
    public function lock(): void
    {
        $this->isLocked = true;
    }

    /**
     * Unlocks the item.
     */
    public function unlock(): void
    {
        $this->isLocked = false;
    }

    public function isLocked(): bool
    {
        return $this->isLocked;
    }

    /**
     * Sends print check-in command to the system.
     */
    public function sendPrintCheckIn(): void
    {
        $this->updateProcess(Process::PRINTING_CHECK_IN);

        $this->raise(new RollWasSentToPrintCheckInEvent(
            rollId: $this->id,
            filmId: $this->filmId,
            size: $this->getPrintedProductsLength()
        ));
    }

    /**
     * Sends the roll to the cutting check-in process.
     *
     * Updates the current process to "Printing Check-In" and raises an event indicating
     * that the roll was sent to cutting check-in.
     */
    public function sendToCutCheckIn(): void
    {
        $this->updateProcess(Process::CUTTING_CHECK_IN);

        $this->raise(new RollWasSentToCutCheckInEvent($this->id));
    }

    /**
     * Sends the item to Glow Check-In process.
     * Updates the process status to GLOW_CHECK_IN and raises an event.
     */
    public function sendToGlowCheckIn(): void
    {
        $this->updateProcess(Process::GLOW_CHECK_IN);

        $this->raise(new RollWasSentToGlowCheckInEvent($this->id));
    }
}
