<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate;

use App\Shared\Domain\Aggregate\Aggregate;
use App\Orders\Domain\ValueObject\OrderType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Order.
 */
final class Order extends Aggregate
{
    /**
     * @phpstan-ignore-next-line
     */
    private ?int $id;
    private ?string $orderNumber = null;
    private ?string $packagingInstructions = null;
    /**
     * @var Collection<Extra>
     */
    private Collection $extras;
    /**
     * @var Collection<Product>
     */
    private Collection $products;
    private readonly \DateTimeInterface $dateAdded;

    /**
     * Initializes a new instance of the class.
     *
     * @param Customer $customer the customer object
     */
    public function __construct(public readonly Customer $customer)
    {
        $this->dateAdded = new \DateTimeImmutable();
        $this->extras = new ArrayCollection([]);
        $this->products = new ArrayCollection([]);
    }

    /**
     * Returns the ID of the object.
     *
     * @return int the ID of the object
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns the order number.
     *
     * @return ?string the order number
     */
    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    /**
     * Returns the date when the entry was added.
     *
     * @return \DateTimeInterface the date when the entry was added
     */
    public function getDateAdded(): \DateTimeInterface
    {
        return $this->dateAdded;
    }

    /**
     * Changes the order number.
     *
     * @param string $orderNumber the new order number
     */
    public function changeOrderNumber(string $orderNumber): void
    {
        $this->orderNumber = $orderNumber;
    }

    /**
     * Adds an Extra to the order.
     *
     * @param Extra $extra The Extra to add
     */
    public function addExtra(Extra $extra): void
    {
        $extra->setOrder($this);
        $this->extras->add($extra);
    }

    /**
     * Returns the collection of extras.
     *
     * @return Collection the collection of extras
     */
    public function getExtras(): Collection
    {
        return $this->extras;
    }

    /**
     * Returns the order type based on the presence of extras.
     *
     * @return OrderType the order type
     */
    public function getOrderType(): OrderType
    {
        if (!$this->extras->isEmpty()) {
            return OrderType::Combined;
        }

        return OrderType::Product;
    }

    /**
     * Returns the packaging instructions.
     *
     * @return ?string the packaging instructions
     */
    public function getPackagingInstructions(): ?string
    {
        return $this->packagingInstructions;
    }

    /**
     * Sets the packaging instructions.
     *
     * @param string $text the packaging instructions
     */
    public function setPackagingInstructions(string $text): void
    {
        $this->packagingInstructions = $text;
    }

    /**
     * Returns the collection of products.
     *
     * @return Collection<Product> the collection of products
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * Adds a product to the order.
     *
     * @param Product $product The product to add to the order
     *
     * @throws \InvalidArgumentException If the given argument is not an instance of Product
     */
    public function addProduct(Product $product): void
    {
        $product->setOrder($this);
        $this->products->add($product);
    }
}
