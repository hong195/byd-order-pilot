<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate;

use App\Orders\Domain\Aggregate\Order\Order;
use App\Orders\Domain\Aggregate\ValueObject\Status;
use Doctrine\Common\Collections\Collection;
use Webmozart\Assert\Assert;

/**
 * Represents a Roll object.
 */
final class Roll
{
    private ?int $id = null;
    private \DateTimeInterface $dateAdded;
    private Status $status = Status::ORDER_CHECK_IN;

    /**
     * @var Collection<Order>
     */
    private Collection $orders;
    private ?Printer $printer = null;

    /**
     * Class constructor.
     *
     * @param string      $name         the name of the object
     * @param string|null $qualityNotes additional notes regarding the quality (default: null)
     */
    public function __construct(private string $name, private ?string $qualityNotes = null)
    {
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
     * Gets the notes for the quality of a lamination.
     *
     * @return string|null the notes for the quality of the item, or null if no notes are available
     */
    public function getQualityNotes(): ?string
    {
        return $this->qualityNotes;
    }

    /**
     * Changes the quality notes of the entity.
     *
     * @param string|null $qualityNotes the new quality notes for the entity
     */
    public function changeQualityNotes(?string $qualityNotes): void
    {
        $this->qualityNotes = $qualityNotes;
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
}
