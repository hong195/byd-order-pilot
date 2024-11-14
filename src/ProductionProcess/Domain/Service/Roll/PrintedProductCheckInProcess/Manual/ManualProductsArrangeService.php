<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual;

use App\ProductionProcess\Domain\Exceptions\ManualArrangeException;
use App\ProductionProcess\Domain\Repository\PrintedProductFilter;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\FilmAssignmentService;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\FilmAvailabilityValidator;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\GroupPrinterService;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Groups\FilmGroup;
use App\ProductionProcess\Domain\Service\Roll\RollMaker;
use Doctrine\Common\Collections\Collection;

final readonly class ManualProductsArrangeService
{
    /**
     * Constructor method for the ManualProductsArrangeService class.
     *
     * @param RollRepositoryInterface           $rollRepository           The Roll Repository Interface being injected
     * @param PrintedProductRepositoryInterface $printedProductRepository The Printed Product Repository Interface being injected
     * @param RollMaker                         $rollMaker                The RollMaker being injected
     */
    public function __construct(
        private RollRepositoryInterface $rollRepository,
        private PrintedProductRepositoryInterface $printedProductRepository,
        private RollMaker $rollMaker,
        private GroupPrinterService $groupPrinterService,
        private FilmAssignmentService $filmAssignmentService,
        private FilmAvailabilityValidator $filmAvailabilityValidator,
        private PrinterValidator $printerValidator,
        private FilmTypeValidator $filmValidator,
    ) {
    }

    /**
     * @throws ManualArrangeException
     */
    public function arrange(array $printedProductIds = []): void
    {
        $printedProducts = $this->printedProductRepository->findByFilter(new PrintedProductFilter(ids: $printedProductIds));

        $this->filmValidator->validate($printedProducts);
        $this->printerValidator->validate($printedProducts);

        $filmGroup = $this->getFilmGroup($printedProducts);
        $this->filmAvailabilityValidator->validate($filmGroup);

        $this->createLockedRollFromFilmGroup($filmGroup);
    }

    /**
     * Creates a locked roll for a given FilmGroup.
     *
     * @param FilmGroup $filmGroup The FilmGroup for which the locked roll needs to be created
     */
    private function createLockedRollFromFilmGroup(FilmGroup $filmGroup): void
    {
        $roll = $this->rollMaker->make(name: "Manual Roll {$filmGroup->filmType}", filmId: $filmGroup->filmId);
        $roll->lock();
        $roll->addPrintedProducts($filmGroup->getPrintedProducts());

        $this->rollRepository->save($roll);
    }

    /**
     * Retrieve a FilmGroup instance based on a collection of printed products.
     *
     * @param Collection $printedProducts A collection of printed products
     *
     * @return ?FilmGroup The FilmGroup instance representing the assigned film group
     */
    public function getFilmGroup(Collection $printedProducts): ?FilmGroup
    {
        $printerGroups = $this->groupPrinterService->group($printedProducts);
        $filmGroups = $this->filmAssignmentService->assignFilmToProductGroups($printerGroups->toArray());

        if (empty($filmGroups)) {
            return null;
        }

        return $filmGroups[0];
    }
}
