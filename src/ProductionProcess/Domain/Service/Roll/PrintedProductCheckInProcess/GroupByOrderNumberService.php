<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;

final class GroupByOrderNumberService
{
    private array $groups = [];

    public function __construct(private readonly GroupByFilmTypeService $groupByFilmTypeService, private readonly ProductGroup $group)
    {
    }

    /**
     * Groups the printed products by film type.
     *
     * @param iterable<PrintedProduct> $printedProducts the list of printed products to group
     *
     * @return ProductGroup[] the grouped printed products where keys are film types
     */
    public function group(iterable $printedProducts): array
    {
        $groupedByFilmType = $this->groupByFilmTypeService->group($printedProducts);

        foreach ($groupedByFilmType as $filmType => $printedProducts) {
            $group = [];

            foreach ($printedProducts as $printedProduct) {
                $group[$filmType][$printedProduct->orderNumber][] = $printedProduct;
            }

            foreach ($group[$filmType] as $orderNumber => $printedProducts2) {
                $this->groups[] = $this->group->make($filmType, $orderNumber, $printedProducts2);
            }

            usort($this->groups, function (ProductGroup $a, ProductGroup $b) {
                return $b->getLength() <=> $a->getLength();
            });
        }

        return $this->groups;
    }
}
