<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Aggregate\Roll;

use App\ProductionProcess\Domain\Aggregate\Order;
use App\ProductionProcess\Domain\Aggregate\Printer;
use App\ProductionProcess\Domain\Aggregate\Product;
use App\ProductionProcess\Domain\Events\RollProcessWasUpdatedEvent;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\ProductionProcess\Domain\ValueObject\Status;
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

    /*
     * Reference to the inventory film
     */
    private ?int $filmId = null;

    private \DateTimeImmutable $dateAdded;
	/**
	 * @var Collection<Order>
	 */
	private Collection $products;
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
        $this->products = new ArrayCollection([]);
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
     * Retrieves the total length of the products associated with this object.
     *
     * @return float the total length of the products associated with this object
     */
    public function getProductsLength(): float
    {
        return $this->products->reduce(fn (int $carry, Order $product) => $carry + $product->getLength(), 0);
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
     * Retrieves the collection of products associated with this object.
     *
     * @return Collection<Order> the collection of products associated with this object
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * Retrieves the number of products associated with this object.
     *
     * @return int the number of products associated with this object
     */
    public function getProductsCount(): int
    {
        return $this->products->count();
    }

    /**
     * Adds an Order to the collection.
     *
     * @param Product $product The product to be added
     */
    public function addProduct(Product $product): void
    {
        $product->changeStatus(Status::ASSIGNED);
        $product->setRoll($this);

        $product->changeSortOrder($this->products->count() + 1);

        $this->products->add($product);
    }

    /**
     * Removes all products from the object.
     */
    public function removeProducts(): void
    {
        foreach ($this->products as $product) {
			$product->removeRoll();
            $this->products->removeElement($product);
        }
    }

    /**
     * Returns the count of priority products.
     *
     * @return int the count of priority products
     */
    public function getProductsWithPriority(): int
    {
        return $this->products->filter(fn (Product $product) => $product->hasPriority())->count();
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
     * @param int $coilId the ID of the coil to be assigned
     */
    public function setFilmId(int $coilId): void
    {
        $this->filmId = $coilId;
    }

    /**
     * Retrieves an array of roll types associated with the products in this object.
     *
     * @return string[] an array of roll types associated with the products in this object
     */
    public function getFilmTypes(): array
    {
        return array_values(array_unique($this->products->map(fn (Product $product) => $product->getFilmType()->value)->toArray()));
    }

    /**
     * Retrieves the lamination types associated with this object.
     *
     * @return string[] an array of lamination types
     */
    public function getLaminations(): array
    {
        return array_values(array_unique($this->products->map(fn (Product $product) => $product->getLaminationType()?->value)->toArray()));
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

	public function setParentRoll(Roll $roll): void
	{
		$this->parentRoll = $roll;
	}

	public function getParentRoll(): ?self
	{
		return $this->parentRoll;
	}

	public function __clone(): void
	{
		if ($this->id) {
			$this->id = null;
			$this->printer = null;
			$this->parentRoll = null;
			$this->dateAdded = new \DateTimeImmutable();
			$this->products = new ArrayCollection([]);
		}
	}
}
