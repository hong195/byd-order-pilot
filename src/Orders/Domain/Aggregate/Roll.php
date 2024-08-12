<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate;

use App\Orders\Domain\ValueObject\Status;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Webmozart\Assert\Assert;

/**
 * Class Roll.
 *
 * Represents a roll in the application.
 */
final class Roll
{
    private ?int $id = null;

    /*
     * Coil id reference to the inventory roll (with available types defined in RollType)
     */
    private ?int $filmId = null;

    private \DateTimeInterface $dateAdded;
    private Status $status = Status::ORDER_CHECK_IN;

    /**
     * @var Collection<Order>
     */
    private Collection $orders;
    private ?Printer $printer = null;

    /**
     * Constructs a new object with the given name, roll type, and lamination types.
     *
     * @param string $name the name of the object
     *
     * @return void
     */
    public function __construct(private string $name, ?int $filmId = null)
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
     * @return \DateTimeInterface the date when the entity was added
     */
    public function getDateAdded(): \DateTimeInterface
    {
        return $this->dateAdded;
    }

    /**
     * Retrieves the total length of the orders associated with this object.
     *
     * @return int the total length of the orders associated with this object
     */
    public function getOrderLength(): int
    {
        return $this->orders->reduce(fn (int $carry, Order $order) => $carry + $order->getLength(), 0);
    }

    /**
     * Retrieves the status associated with this object.
     *
     * @return Status the status associated with this object
     */
    public function getStatus(): Status
    {
        return $this->status;
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
     * Adds an Order to the collection.
     *
     * @param Order $order The order to be added
     */
    public function addOrder(Order $order): void
    {
        $this->orders->add($order);
    }

    /**
     * Removes all orders from the object.
     */
    public function removeOrders(): void
    {
        $this->orders->clear();
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
     * @return int the coil ID associated with this object
     */
    public function getFilmId(): int
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
}
