<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate\OrderStack;

use App\Orders\Domain\Aggregate\Order\Order;
use App\Orders\Domain\Aggregate\Roll\RollType;
use App\Orders\Domain\Aggregate\ValueObject\LaminationType;
use App\Orders\Domain\Aggregate\ValueObject\Status;
use App\Shared\Domain\Aggregate\Aggregate;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Represents an order stack.
 */
final class OrderStack extends Aggregate
{
    /**
     * @phpstan-ignore-next-line
     */
    private ?int $id;
    private Status $status = Status::ORDER_CHECK_IN;
    private \DateTimeImmutable $dateAdded;

    private \DateTime $updatedAt;

    /**
     * @var ArrayCollection<Order>
     */
    private Collection $orders;

    /**
     * Class Constructor.
     *
     * @param string              $name           the name of the application
     * @param int                 $length         the length of the application
     * @param RollType            $rollType       the roll type of the application
     * @param LaminationType|null $laminationType the lamination type of the application
     */
    public function __construct(public readonly string $name, public readonly int $length, private readonly RollType $rollType, private readonly ?LaminationType $laminationType = null)
    {
        $this->orders = new ArrayCollection();
        $this->dateAdded = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Get the id of the object.
     *
     * @return int|null The id of the object or null if it has not been set
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the status of the application.
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * Get the datetime when the object was added.
     *
     * @return \DateTimeImmutable the datetime when the object was added
     */
    public function getDateAdded(): \DateTimeImmutable
    {
        return $this->dateAdded;
    }

    /**
     * Get the date and time when the entity was last updated.
     *
     * @return \DateTime the value of updatedAt property
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Get the name of the application.
     *
     * @return string the name of the application
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the orders of the order stack sorted by priority.
     *
     * @return Collection<Order> the orders of the OrderStack
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * Add an order to the application.
     *
     * @param Order $order the order to add
     */
    public function addOrder(Order $order): void
    {
        $this->orders->add($order);
    }

    /**
     * Delete all orders.
     */
    public function deleteOrders(): void
    {
        $this->orders->clear();
    }

    /**
     * Get the roll type of the object.
     *
     * @return RollType the roll type of the object
     */
    public function getRollType(): RollType
    {
        return $this->rollType;
    }

    /**
     * Get the lamination type of the current object.
     *
     * @return ?LaminationType the lamination type object
     */
    public function getLaminationType(): ?LaminationType
    {
        return $this->laminationType;
    }

    /**
     * Retrieves the number of priority orders.
     *
     * @return Order[] the number of priority orders
     */
    public function getPriorityOrders(): array
    {
        return array_filter($this->orders->toArray(), fn (Order $order) => $order->hasPriority());
    }

    /**
     * Returns the count of priority orders.
     *
     * @return int|null the count of priority orders, or null if there are no priority orders
     */
    public function getPriorityOrdersCount(): ?int
    {
        return count($this->getPriorityOrders());
    }

    /**
     * Get the total length of all orders associated with the current object.
     *
     * @return int the total length of all orders
     */
    public function getLength(): int
    {
        $orders = $this->getOrders();

        $length = 0;

        foreach ($orders as $order) {
            $length += $order->getLength();
        }

        return $length;
    }

    /**
     * Calculates the total length of all orders.
     *
     * @return int returns the total length of all orders
     */
    public function getOrdersLength(): int
    {
        return array_reduce($this->orders->toArray(), function ($carry, Order $order): int {
            return $carry + $order->getLength();
        }, 0);
    }

    /**
     * Determines if an order can be added based on the current orders' length and the order's length.
     *
     * @param Order $order the order to be added
     *
     * @return bool returns true if the order can be added, otherwise returns false
     */
    public function canAddOrder(Order $order): bool
    {
        return $this->getOrdersLength() + $order->getLength() <= $this->length;
    }
}
