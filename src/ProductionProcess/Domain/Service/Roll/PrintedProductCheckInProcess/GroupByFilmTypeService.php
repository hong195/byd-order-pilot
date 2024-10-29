<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;

final class GroupByFilmTypeService
{
    private array $groups;

    /**
     * Groups by order number the given printed products.
     *
     * @param PrintedProduct[] $printedProducts The printed products to be grouped
     *
     * @return array<string, PrintedProduct[]> The grouped printed products
     */
    public function group(iterable $printedProducts): array
    {
        foreach ($printedProducts as $printedProduct) {
            $this->groups[$printedProduct->filmType][] = $printedProduct;
        }

        return $this->groups;
    }
}
