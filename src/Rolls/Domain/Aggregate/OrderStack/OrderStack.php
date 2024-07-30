<?php

declare(strict_types=1);

namespace App\Rolls\Domain\Aggregate\OrderStack;

use App\Rolls\Domain\Aggregate\Lamination\LaminationType;
use App\Rolls\Domain\Aggregate\Order\Order;
use App\Rolls\Domain\Aggregate\Order\ValueObject\Status;
use App\Rolls\Domain\Aggregate\Roll\RollType;
use App\Rolls\Domain\Service\SortOrdersServiceInterface;
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
    private Status $status = Status::NEW;
    private \DateTimeImmutable $dateAdded;

    private \DateTimeImmutable $updatedAt;

    /**
     * @var ArrayCollection<Order>
     */
    private Collection $orders;

    /**
     * Class constructor.
     *
     * @param string                     $name              The name of the object
     * @param int                        $length            The length of the object
     * @param int                        $priority          The priority of the object
     * @param RollType                   $rollType          The roll type object associated with the object
     * @param SortOrdersServiceInterface $sortOrdersService The sort orders service interface object associated with the object
     * @param LaminationType|null        $laminationType    The lamination type object associated with the object (optional)
     */
    public function __construct(
        private string $name,
        private int $length,
        private int $priority,
        private readonly RollType $rollType,
        private readonly SortOrdersServiceInterface $sortOrdersService,
        private readonly ?LaminationType $laminationType = null,
    ) {
        $this->orders = new ArrayCollection();
        $this->dateAdded = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
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
    public function getAddedAt(): \DateTimeImmutable
    {
        return $this->dateAdded;
    }

    /**
     * Get the date and time when the entity was last updated.
     *
     * @return \DateTimeImmutable the value of updatedAt property
     */
    public function getUpdatedAt(): \DateTimeImmutable
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
     * Get the length of the object.
     *
     * @return int the length of the object
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * Get the orders of the order stack sorted by priority.
     *
     * @return Collection<Order> the orders of the OrderStack
     */
    public function getOrders(): Collection
    {
        return $this->sortOrdersService->getSorted($this->orders);
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
     * Change the priority of the current object.
     *
     * @param int $priority the new priority value
     */
    public function changePriority(int $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * Get the priority value of the current object.
     *
     * @return int The priority value
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Get the total length of all orders associated with the current object.
     *
     * @return int the total length of all orders
     */
    public function getOrdersLength(): int
    {
        $orders = $this->getOrders();

        $length = 0;

        foreach ($orders as $order) {
            $length += $order->getLength();
        }

        return $length;
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

    /**
     * Remove all orders from the current object.
     */
    public function removeOrders(): void
    {
        $this->orders = new ArrayCollection();
    }
}
