<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Auto;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductFilter;
use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\GroupByOrderNumberService;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\GroupPrinterService;
use App\ProductionProcess\Domain\Service\Roll\RollMaker;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class AutoCheckInPrintedProductsService
{
    private Collection $printedProducts;

    private Collection $unassignedPrintedProducts;

    /**
     * Constructor for initializing dependencies.
     */
    public function __construct(
        private readonly PrintedProductRepositoryInterface $printedProductRepository,
        private readonly RollRepositoryInterface $rollRepository,
        private readonly RollMaker $rollMaker,
        private readonly GroupByOrderNumberService $groupByOrderNumberService,
        private readonly FilmAssignmentService $filmAssignmentService,
        private readonly GroupPrinterService $groupPrinterService,
    ) {
    }

    /**
     * Arranges printed products into rolls based on film type and suitable film availability.
     *
     * @param int[] $printedProductIds An array of printed product IDs to arrange (optional)
     *
     * @return int[] An array of printed product IDs that could not be assigned to a roll
     */
    public function arrange(array $printedProductIds = []): array
    {
        // TODO move to a separate service
        $this->initPrintedProducts($printedProductIds);

        $printerGroups = $this->groupPrinterService->group($this->printedProducts);
        $groupedByOrderNumber = $this->groupByOrderNumberService->group($printerGroups);
        $filmGroups = $this->filmAssignmentService->assignFilmToProductGroups($groupedByOrderNumber);

        foreach ($filmGroups as $filmGroup) {
            if (!$filmGroup->filmId) {
                array_map(fn (PrintedProduct $printedProduct) => $this->unassignedPrintedProducts->add($printedProduct), $filmGroup->getPrintedProducts());
                continue;
            }

            foreach ($filmGroup->getGroups() as $group) {
                $roll = $this->rollMaker->make(name: "Roll {$filmGroup->filmType}", filmId: $filmGroup->filmId);
                $roll->addPrintedProducts($group->getPrintedProducts());
                $roll->assignPrinter($group->getPrinter());
                $this->rollRepository->save($roll);
            }
        }

        return $this->unassignedPrintedProducts->map(fn (PrintedProduct $printedProduct) => $printedProduct->getId())->toArray();
    }

    /**
     * Initialize printed products based on given printed product IDs.
     *
     * @param int[] $printedProductsIds Array of printed product IDs
     */
    private function initPrintedProducts(array $printedProductsIds): void
    {
        $rolls = $this->rollRepository->findForAutoArrange();

        $this->printedProducts = new ArrayCollection();
        $this->unassignedPrintedProducts = new ArrayCollection();

        $printedProductsIds = array_merge(
            $printedProductsIds,
            ...$rolls->map(fn (Roll $roll) => $roll->getPrintedProducts()->map(fn (PrintedProduct $product) => $product->getId())->toArray())->toArray()
        );

        $assignablePrintedProducts = $this->printedProductRepository->findByFilter(
            new PrintedProductFilter(ids: $printedProductsIds)
        );

        foreach ($assignablePrintedProducts as $printedProduct) {
            $this->printedProducts->add($printedProduct);
        }

        /** @var Roll $roll */
        foreach ($rolls as $roll) {
            $roll->removePrintedProducts();
            $this->rollRepository->remove($roll);
        }
    }
}
