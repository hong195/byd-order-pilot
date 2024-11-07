<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Groups;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Printer\Printer;

/**
 * Class ProductGroup.
 *
 * This class represents a group of products with order information, printed products, and film type.
 */
final class ProductGroup
{
    private ?Printer $printer = null;

    /**
     * Class constructor.
     *
     * @param string|null      $orderNumber     the order number (nullable)
     * @param PrintedProduct[] $printedProducts the printed products array (nullable)
     * @param string|null      $filmType        the film type (nullable)
     */
    public function __construct(public readonly ?string $orderNumber = null, public readonly ?array $printedProducts = [], public readonly ?string $filmType = null)
    {
    }

    /**
     * Creates a new instance of the current class with the specified film type, order number, and printed products.
     *
     * @param string           $filmType        the type of film
     * @param string           $orderNumber     the order number
     * @param PrintedProduct[] $printedProducts an array of printed products
     *
     * @return self a new instance of the current class with the specified parameters
     */
    public function make(string $filmType, string $orderNumber, array $printedProducts): self
    {
        return new self($orderNumber, $printedProducts, $filmType);
    }

    /**
     * Assigns a Printer object to this object.
     *
     * @param Printer $printer The Printer object to be assigned
     */
    public function assignPrinter(Printer $printer): void
    {
        $this->printer = $printer;
    }

    /**
     * Retrieves the Printer associated with this object, if any.
     *
     * @return Printer|null The Printer object associated with this object, or null if none is set
     */
    public function getPrinter(): ?Printer
    {
        return $this->printer;
    }

    /**
     * Calculate the total length of printed products.
     *
     * @return int|float the total length of printed products as integer or float
     */
    public function getLength(): int|float
    {
        return array_sum(array_map(fn ($printedProduct) => $printedProduct->getLength(), $this->printedProducts));
    }

    /**
     * Retrieves the products stored in the database.
     *
     * @return PrintedProduct[] the products stored in the database
     */
    public function getPrintedProducts(): array
    {
        return $this->printedProducts;
    }
}
