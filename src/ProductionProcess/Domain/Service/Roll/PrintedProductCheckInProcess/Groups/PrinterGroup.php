<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Groups;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Printer\Printer;

final class PrinterGroup
{
    /**
     * Constructor for the Symfony application.
     *
     * @param Printer|null     $printer  The printer object, can be null
     * @param PrintedProduct[] $products An array containing groups
     */
    public function __construct(public ?Printer $printer = null, private array $products = [])
    {
    }

    /**
     * Adds a product to the group.
     *
     * @param PrintedProduct $product The product group to be added
     */
    public function addProductTo(PrintedProduct $product): void
    {
        $this->products[] = $product;
    }

    /**
     * Get the groups associated with the film.
     *
     * @return PrintedProduct[] An array of groups associated with the film
     */
    public function getProducts(): array
    {
        return $this->products;
    }
}
