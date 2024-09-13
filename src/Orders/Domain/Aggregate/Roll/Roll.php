<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate\Roll;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Aggregate\Printer;
use App\Orders\Domain\Events\RollProcessWasUpdatedEvent;
use App\Orders\Domain\ValueObject\Process;
use App\Orders\Domain\ValueObject\Status;
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
    private Collection $orders;
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
        $this->orders = new ArrayCollection();
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
     * Retrieves the total length of the orders associated with this object.
     *
     * @return int the total length of the orders associated with this object
     */
    public function getOrdersLength(): int
    {
        return $this->orders->reduce(fn (int $carry, Order $order) => $carry + $order->getLength(), 0);
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
     * Retrieves the collection of orders associated with this object.
     *
     * @return Collection<Order> the collection of orders associated with this object
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * Retrieves the number of orders associated with this object.
     *
     * @return int the number of orders associated with this object
     */
    public function getOrdersCount(): int
    {
        return $this->orders->count();
    }

    /**
     * Adds an Order to the collection.
     *
     * @param Order $order The order to be added
     */
    public function addOrder(Order $order): void
    {
        $order->changeStatus(Status::ASSIGNED);
        $order->setRoll($this);

        $order->changeSortOrder($this->orders->count() + 1);

        $this->orders->add($order);
    }

    /**
     * Removes all orders from the object.
     */
    public function removeOrders(): void
    {
        foreach ($this->orders as $order) {
            $order->removeRoll();
            $this->orders->removeElement($order);
        }
    }

    /**
     * Returns the count of priority orders.
     *
     * @return int the count of priority orders
     */
    public function getPriorityOrders(): int
    {
        return $this->orders->filter(fn (Order $order) => $order->hasPriority())->count();
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
     * Retrieves an array of roll types associated with the orders in this object.
     *
     * @return string[] an array of roll types associated with the orders in this object
     */
    public function getFilmTypes(): array
    {
        return array_values(array_unique($this->orders->map(fn (Order $order) => $order->getFilmType()->value)->toArray()));
    }

    /**
     * Retrieves the lamination types associated with this object.
     *
     * @return string[] an array of lamination types
     */
    public function getLaminations(): array
    {
        return array_values(array_unique($this->orders->map(fn (Order $order) => $order->getLaminationType()?->value)->toArray()));
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
			$this->orders = new ArrayCollection([]);
		}
	}
}
