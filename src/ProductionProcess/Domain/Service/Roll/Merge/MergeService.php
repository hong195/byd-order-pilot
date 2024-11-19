<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\Merge;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\DTO\FilmData;
use App\ProductionProcess\Domain\Exceptions\InventoryFilmIsNotAvailableException;
use App\ProductionProcess\Domain\Exceptions\RollMergeException;
use App\ProductionProcess\Domain\Repository\Roll\RollFilter;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual\ManualArrangementValidator;
use App\ProductionProcess\Domain\Service\Roll\RollMaker;
use App\Shared\Domain\Exception\DomainException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final readonly class MergeService
{
    public function __construct(private RollRepositoryInterface $rollRepository, private RollMaker $rollMaker, private ManualArrangementValidator $arrangementValidator, private AvailableFilmServiceInterface $availableFilmService)
    {
    }

    /**
     * Merges rolls based on the provided roll IDs.
     *
     * @param int[] $rollIds An array of roll IDs to be merged
     *
     * @return int The ID of the merged roll
     *
     * @throws DomainException
     * @throws RollMergeException
     */
    public function merge(array $rollIds): int
    {
        $rollsToMerge = $this->rollRepository->findByFilter(new RollFilter(rollIds: $rollIds));

        if ($rollsToMerge->count() < 2) {
            RollMergeException::because('You need at least two rolls to merge');
        }

        $products = $this->getProducts($rollsToMerge);
        $productsLength = array_sum($products->map(fn (PrintedProduct $product) => $product->getLength())->toArray());

        if ($products->isEmpty()) {
            RollMergeException::because('One of the rolls does not have printed products');
        }

        $this->arrangementValidator->validate($products);

        $availableFilm = $this->getFilm($rollsToMerge, $productsLength);

        dd($availableFilm);

        if (!$availableFilm) {
            InventoryFilmIsNotAvailableException::because('Film is not available');
        }

        $mergedRoll = $this->makeMergedAndLockedRoll(filmId: $availableFilm->id, printedProducts: $products);

        $this->removeRollsToMerge($rollsToMerge);

        return $mergedRoll->getId();
    }

    /**
     * Retrieves available film based on a collection of rolls to exclude.
     *
     * @param Collection<Roll> $rollsToExclude A collection of Roll objects to exclude
     *
     * @return ?FilmData The available FilmData object based on the rolls to exclude
     */
    public function getFilm(Collection $rollsToExclude, float $minLength): ?FilmData
    {
        $usedFilmIds = $rollsToExclude->map(fn (Roll $roll) => $roll->getFilmId())->toArray();

        $availableFilms = $this->availableFilmService->getAvailableFilms(
            $rollsToExclude->first()->getPrintedProducts()->first()->getFilmType()
        )
            ->filter(fn (FilmData $filmData) => $filmData->length >= $minLength)
            ->filter(
                fn (FilmData $filmData) => !in_array($filmData->id, $usedFilmIds, true)
            );

        foreach ($availableFilms as $film) {
            $rolls = $this->rollRepository->findByFilmId($film->id);

			dd($rolls);
        }
    }

    /**
     * Creates a merged and locked Roll from a collection of PrintedProducts.
     *
     * @param Collection<PrintedProduct> $printedProducts A collection of PrintedProduct objects
     *
     * @return Roll The merged and locked Roll object
     */
    private function makeMergedAndLockedRoll(int $filmId, Collection $printedProducts): Roll
    {
        $mergedRoll = $this->rollMaker->make(name: 'Merged Roll');

        $mergedRoll->setFilmId($filmId);
        $mergedRoll->addPrintedProducts($printedProducts);
        $mergedRoll->lock();

        $this->rollRepository->save($mergedRoll);

        return $mergedRoll;
    }

    /**
     * Retrieves printed products from a collection of rolls.
     *
     * @param Collection<Roll> $rolls A collection of Roll objects
     *
     * @return Collection<PrintedProduct> A collection of PrintedProduct objects
     */
    private function getProducts(Collection $rolls): Collection
    {
        $products = new ArrayCollection();

        foreach ($rolls as $roll) {
            foreach ($roll->getPrintedProducts() as $printedProduct) {
                $products->add($printedProduct);
            }
        }

        return $products;
    }

    /**
     * Removes rolls from the repository that are marked for merging.
     *
     * @param Collection<Roll> $rollsToMerge A collection of Roll objects to be removed
     */
    private function removeRollsToMerge(Collection $rollsToMerge): void
    {
        foreach ($rollsToMerge as $roll) {
            $this->rollRepository->remove($roll);
        }
    }
}
