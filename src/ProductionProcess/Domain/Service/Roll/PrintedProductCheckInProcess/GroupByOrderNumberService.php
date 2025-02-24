<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Auto\Groups\PrinterGroup;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Auto\Groups\ProductGroup;

final class GroupByOrderNumberService
{
    public function __construct(private readonly GroupByFilmTypeService $groupByFilmTypeService, private readonly ProductGroup $group)
    {
    }

    /**
     * Groups the printed products by film type.
     *
     * @param iterable<PrinterGroup> $printerGroups the list of printed products to group
     *
     * @return ProductGroup[] the grouped printed products where keys are film types
     */
    public function group(iterable $printerGroups): array
    {
		$groups = [];

        foreach ($printerGroups as $group) {
            $printedProducts = $group->getProducts();

            if (empty($printedProducts)) {
                continue;
            }

            $groupedByFilmType = $this->groupByFilmTypeService->group($printedProducts);
            $printer = $group->printer;

            foreach ($groupedByFilmType as $filmType => $printedProducts) {
                $group = [];

                foreach ($printedProducts as $printedProduct) {
                    $group[$filmType][$printedProduct->orderNumber][] = $printedProduct;
                }

                foreach ($group[$filmType] as $orderNumber => $printedProducts2) {
                    $group = $this->group->make($filmType, $orderNumber, $printedProducts2);
                    $group->assignPrinter($printer);

					$groups[] = $group;
                }

                usort($groups, function (ProductGroup $a, ProductGroup $b) {
                    return $b->getLength() <=> $a->getLength();
                });
            }
        }

        return $groups;
    }
}
