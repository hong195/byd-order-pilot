<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Exceptions\InventoryFilmIsNotAvailableException;
use App\ProductionProcess\Domain\Exceptions\ManualArrangeException;
use App\ProductionProcess\Domain\Repository\PrintedProductFilter;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollFilter;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\Service\Printer\ProductPrinterMatcher;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Groups\FilmGroup;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Groups\ProductGroup;
use App\ProductionProcess\Domain\Service\Roll\RollMaker;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Exception\DomainException;
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
        private ManualArrangementValidator $arrangementValidator,
        private ProductPrinterMatcher $productPrinterMatcher,
        private AvailableFilmServiceInterface $availableFilmService,
    ) {
    }

    /**
     * @throws ManualArrangeException
     * @throws DomainException
     */
    public function arrange(array $printedProductIds = []): void
    {
        $printedProducts = $this->printedProductRepository->findByFilter(new PrintedProductFilter(ids: $printedProductIds));

        $this->arrangementValidator->validate($printedProducts);

        $printer = $this->productPrinterMatcher->match($printedProducts[0]);
        $filmGroup = $this->getFilmGroup($printedProducts);

        $roll = $this->rollMaker->make(name: "Manual Roll {$filmGroup->filmType}", filmId: $filmGroup->filmId);
        $roll->lock();
        $roll->assignPrinter($printer);
        $roll->addPrintedProducts($filmGroup->getPrintedProducts());

        $this->rollRepository->save($roll);
    }

    /**
     * Retrieve a FilmGroup instance based on a collection of printed products.
     *
     * @param Collection $printedProducts A collection of printed products
     *
     * @return FilmGroup The FilmGroup instance representing the assigned film group
     *
     * @throws DomainException
     */
    public function getFilmGroup(Collection $printedProducts): FilmGroup
    {
        $printedProductsLength = array_sum($printedProducts->map(fn (PrintedProduct $pp) => $pp->getLength())->toArray());
        $filmType = $printedProducts->first()->getFilmType();

        $availableFilms = $this->availableFilmService->getAvailableFilms(filmType: $filmType, minSize: $printedProductsLength);

        if ($availableFilms->isEmpty()) {
            InventoryFilmIsNotAvailableException::because('Not found film');
        }

        foreach ($availableFilms as $film) {
            $rollInArrangeProcess = $this->getRollsLength([$film->id]);

            if ($film->length >= $printedProductsLength + $rollInArrangeProcess) {
                $filmGroup = new FilmGroup(filmId: $film->id, filmType: $filmType);

                $filmGroup->addProductGroup(new ProductGroup(printedProducts: $printedProducts->toArray()));

                return $filmGroup;
            }
        }

        throw InventoryFilmIsNotAvailableException::because('Not enough film');
    }

    /**
     * Retrieves the total length of rolls for a given film ID.
     *
     * @param int[] $filmIds The ID of the film for which the rolls length needs to be calculated
     *
     * @return float The total length of rolls for the specified film ID
     */
    private function getRollsLength(array $filmIds): float
    {
        $rolls = $this->rollRepository->findByFilter(new RollFilter(process: Process::ORDER_CHECK_IN, filmIds: $filmIds));

        return array_sum($rolls->map(fn (Roll $roll) => $roll->getPrintedProductsLength())->toArray());
    }
}
