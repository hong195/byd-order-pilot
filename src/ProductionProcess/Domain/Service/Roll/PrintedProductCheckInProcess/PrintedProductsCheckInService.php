<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Repository\PrintedProductFilter;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollFilter;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\RollMaker;
use App\ProductionProcess\Domain\ValueObject\Process;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class PrintedProductsCheckInService
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
     * Initializes the printedProducts in the application.
     *
     * This method retrieves the printedProducts with status "assignable" from the printedProduct repository,
     * adds them to the $printedProducts collection, and then adds the printedProducts associated with each
     * roll in the $rolls collection to the $printedProducts collection. Finally, it sorts the
     * $printedProducts collection using the SortPrintedProductsService.
     */
    private function initPrintedProducts(array $printedProductsIds): void
    {
        $rolls = new ArrayCollection($this->rollRepository->findByFilter(new RollFilter(process: Process::ORDER_CHECK_IN)));

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
