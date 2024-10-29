<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollFilter;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\RollMaker;
use App\ProductionProcess\Domain\ValueObject\Process;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class MaxMinReArrangePrintedProductService.
 */
final class PrintedProductsCheckInService implements PrintedProductCheckInInterface
{
    private Collection $rolls;
    private Collection $printedProducts;

    /**
     * Constructor for initializing dependencies.
     */
    public function __construct(
        private readonly PrintedProductRepositoryInterface $printedProductRepository,
        private readonly RollRepositoryInterface $rollRepository,
        private readonly RollMaker $rollMaker,
        private readonly GroupByOrderNumberService $groupByOrderNumberService,
        private readonly FilmAssignmentService $findSuitableRollForArrangement,
    ) {
    }

    /**
     * Arranges printed products into rolls based on film type and suitable film availability.
     *
     * @param int[] $printedProductIds An array of printed product IDs to arrange (optional)
     */
    public function arrange(array $printedProductIds = []): void
    {
        $this->initData();

        $groups = $this->groupByOrderNumberService->group($this->printedProducts);

        $filmGroups = $this->findSuitableRollForArrangement->assignFilmToProductGroups($groups);

        foreach ($filmGroups as $filmGroup) {
            if (!$filmGroup->filmId) {
                continue;
            }

            $roll = $this->rollMaker->make(name: "Roll {$filmGroup->filmType}", filmId: $filmGroup->filmId, filmType: $filmGroup->filmType);

            $roll->removePrintedProducts();

            $roll->addPrintedProducts($filmGroup->getPrintedProducts());

            $this->rollRepository->save($roll);
        }
    }

    /**
     * Initializes the data for the printedProducts check in, uses latest rolls and printedProducts to do that.
     */
    private function initData(): void
    {
        $this->rolls = new ArrayCollection($this->rollRepository->findByFilter(new RollFilter(process: Process::ORDER_CHECK_IN)));

        $this->initPrintedProducts();
    }

    /**
     * Initializes the printedProducts in the application.
     *
     * This method retrieves the printedProducts with status "assignable" from the printedProduct repository,
     * adds them to the $printedProducts collection, and then adds the printedProducts associated with each
     * roll in the $rolls collection to the $printedProducts collection. Finally, it sorts the
     * $printedProducts collection using the SortPrintedProductsService.
     */
    private function initPrintedProducts(): void
    {
        $this->printedProducts = new ArrayCollection();
        $assignablePrintedProducts = $this->printedProductRepository->findUnassign();

        foreach ($assignablePrintedProducts as $printedProduct) {
            $this->printedProducts->add($printedProduct);
        }

        /** @var Roll $roll */
        foreach ($this->rolls as $roll) {
            foreach ($roll->getPrintedProducts() as $printedProduct) {
                $this->printedProducts->add($printedProduct);
            }

            $roll->removePrintedProducts();
        }

        $this->rollRepository->remove($roll);
    }
}
