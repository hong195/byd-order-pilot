<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

final readonly class ProductGroup
{
    public function __construct(public ?string $orderNumber = null, public ?array $printedProducts = [], public ?string $filmType = null)
    {
    }

    public function make(string $filmType, string $orderNumber, array $printedProducts): self
    {
        return new self($orderNumber, $printedProducts, $filmType);
    }

    public function getLength(): int|float
    {
        return array_sum(array_map(fn ($printedProduct) => $printedProduct->getLength(), $this->printedProducts));
    }
}
