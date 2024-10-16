<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\DTO\FilmData;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollFilter;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\Service\PrintedProduct\SortService as PrintedProductSortService;
use App\ProductionProcess\Domain\Service\Roll\RollMaker;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\ProductionProcess\Domain\ValueObject\Status;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class MaxMinReArrangePrintedProductService.
 */
final class PrintedProductsCheckInService implements PrintedProductCheckInInterface
{
    private Collection $assignedRolls;
    private Collection $rolls;
    private Collection $printedProducts;

    /**
     * Class constructor.
     *
     * @param PrintedProductSortService     $sortPrintedProductsService The sort jobs service
     * @param RollRepositoryInterface       $rollRepository             The roll repository
     * @param AvailableFilmServiceInterface $availableFilmService       The available film service
     * @param RollMaker                     $rollMaker                  The roll maker
     */
    public function __construct(
        private readonly PrintedProductRepositoryInterface $printedProductRepository,
        private readonly PrintedProductSortService $sortPrintedProductsService,
        private readonly RollRepositoryInterface $rollRepository,
        private readonly AvailableFilmServiceInterface $availableFilmService,
        private readonly RollMaker $rollMaker,
    ) {
    }

    /**
     * Performs the check-in process for the current session.
     *
     * @throws \Exception if an error occurs during the check-in process
     */
    public function checkIn(): void
    {
        $this->initData();

        $availableFilms = $this->availableFilmService->getAvailableFilms();
        $groupedFilms = $this->groupFilmsByType($availableFilms);
        $groupedPrintedProducts = $this->groupPrintedProductsByFilm($this->printedProducts);

        foreach ($groupedPrintedProducts as $filmType => $printedProducts) {
            if (!isset($groupedFilms[$filmType])) {
                // If there is no film of this type, create an empty roll for all printedProducts of this type
                foreach ($printedProducts as $printedProduct) {
                    $roll = $this->findOrMakeRoll(name: "Empty Roll {$printedProduct->getFilmType()}", filmType: $printedProduct->getFilmType());
                    $roll->addPrintedProduct($printedProduct);
                    $this->syncAssignRolls($roll);
                }
                continue;
            }

            // Инициализируем доступные пленки для данного типа
            $currentFilm = $groupedFilms[$filmType];

            foreach ($printedProducts as $printedProduct) {
                $printedProductPlaced = false;

                // Attempting to place a printedProduct on existing film rolls
                foreach ($currentFilm as $key => $film) {
                    $filmLength = $film->length;

                    $roll = $this->findOrMakeRoll(name: "Roll {$film->filmType}", filmId: $film->id, filmType: $printedProduct->getFilmType());

                    if ($roll->getPrintedProductsLength() + $printedProduct->getLength() <= $filmLength) {
                        $roll->addPrintedProduct($printedProduct);

                        $this->syncAssignRolls($roll);
                        $printedProductPlaced = true;

                        if (0 === $filmLength) {
                            unset($currentFilm[$key]); // Remove the film from the available films
                        }

                        break;
                    }
                }

                // Если заказ не был размещен, создаем пустой рулон
                if (!$printedProductPlaced) {
                    $roll = $this->findOrMakeRoll("Empty Roll {$printedProduct->getFilmType()}", null, $printedProduct->getFilmType());
                    $roll->addPrintedProduct($printedProduct);

                    $this->syncAssignRolls($roll);
                }
            }
        }

        $this->saveRolls();
    }

    /**
     * Initializes the data for the printedProducts check in, uses latest rolls and printedProducts to do that.
     */
    private function initData(): void
    {
        $this->assignedRolls = new ArrayCollection([]);
        $this->rolls = new ArrayCollection($this->rollRepository->findByFilter(new RollFilter(process: Process::ORDER_CHECK_IN)));

        $this->initPrintedProducts();
    }

    /**
     * Finds or makes a roll based on the given parameters.
     *
     * @param string      $name     The name of the roll
     * @param int|null    $filmId   The ID of the film associated with the roll (optional)
     * @param string|null $filmType The roll type associated with the roll (optional)
     *
     * @return Roll The found or newly created roll
     */
    private function findOrMakeRoll(string $name, ?int $filmId = null, ?string $filmType = null): Roll
    {
        $roll = $this->rolls->filter(function (Roll $roll) use ($filmId, $filmType) {
            return $roll->getFilmId() === $filmId && in_array($filmType, $roll->getPrinter()?->getFilmTypes() ?? []);
        })->first();

        if ($roll) {
            return $roll;
        }

        $roll = $this->rollMaker->make($name, $filmId, $filmType, Process::ORDER_CHECK_IN);

        $this->rolls->add($roll);

        return $roll;
    }

    /**
     * Syncs the assigned rolls with a new roll.
     *
     * @param Roll $roll The roll to sync with
     */
    private function syncAssignRolls(Roll $roll): void
    {
        // if roll was added previously to assignedRolls, remove it and add it again
        if ($this->assignedRolls->contains($roll)) {
            $this->assignedRolls->removeElement($roll);
        }

        $this->assignedRolls->add($roll);
    }

    /**
     * Groups printedProducts by roll type.
     *
     * @param Collection<PrintedProduct> $printedProducts the collection of printedProducts
     *
     * @return array<string, PrintedProduct[]> the array of grouped printedProducts
     */
    private function groupPrintedProductsByFilm(Collection $printedProducts): array
    {
        $groupedPrintedProducts = [];

        foreach ($printedProducts as $printedProduct) {
            $groupedPrintedProducts[$printedProduct->getFilmType()][] = $printedProduct;
        }

        return $groupedPrintedProducts;
    }

    /**
     * Groups films by roll type.
     *
     * @param Collection<FilmData> $films the collection of films
     *
     * @return array<string, FilmData[]> the array of grouped films
     */
    private function groupFilmsByType(Collection $films): array
    {
        $groupedFilms = [];

        foreach ($films as $film) {
            $groupedFilms[$film->filmType][] = $film;
        }

        return $groupedFilms;
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
        $assignablePrintedProducts = $this->printedProductRepository->findByStatus(Status::ASSIGNABLE);

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

        $this->printedProducts = $this->sortPrintedProductsService->getSorted($this->printedProducts);
    }

    /**
     * Saves the assigned rolls.
     *
     * If a roll has no printedProducts associated with it, it will be removed from the repository.
     */
    private function saveRolls(): void
    {
        foreach ($this->rolls as $roll) {
            $this->syncAssignRolls($roll);
        }

        foreach ($this->assignedRolls as $roll) {
            if ($roll->getPrintedProducts()->isEmpty()) {
                $this->rollRepository->remove($roll);
                continue;
            }

            $this->rollRepository->save($roll);
        }
    }
}
