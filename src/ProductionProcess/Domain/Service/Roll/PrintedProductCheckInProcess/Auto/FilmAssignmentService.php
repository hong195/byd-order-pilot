<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Auto;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Repository\Roll\RollFilter;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Auto\Groups\FilmGroup;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Auto\Groups\ProductGroup;
use App\ProductionProcess\Domain\ValueObject\Process;
use Doctrine\Common\Collections\ArrayCollection;

final class FilmAssignmentService
{
    /**
     * @var FilmGroup[]
     */
    public array $filmGroups = [];

    /**
     * Constructor method for the class.
     *
     * @param AvailableFilmServiceInterface $availableFilmService An object of AvailableFilmServiceInterface
     * @param FilmGroup                     $filmGroup            An object of FilmGroup
     */
    public function __construct(private AvailableFilmServiceInterface $availableFilmService, private FilmGroup $filmGroup, private RollRepositoryInterface $rollRepository)
    {
    }

    /**
     * Assign films to order groups based on the film type and total length of each group.
     *
     * @param ProductGroup[] $groups An array of order groups to assign films to
     *
     * @return FilmGroup[] An array of order groups with assigned films
     */
    public function assignFilmToProductGroups(array $groups): array
    {
        foreach ($groups as $group) {
            $isPlaced = false;

            $availableFilms = $this->availableFilmService->getAvailableFilmsByType(filmType: $group->filmType, minSize: $group->getLength());

            if ($availableFilms->isEmpty()) {
                $this->handleNoAvailableFilms($group);
                continue;
            }

            foreach ($availableFilms as $film) {
                // take into account the length of manually created rolls with the same film
                $rollsLength = $this->getManuallyCreatedRollsLengthByFilmId($film->id);

                if (!isset($this->filmGroups[$film->id])) {
                    if ($group->getLength() + $rollsLength > $film->length) {
                        continue;
                    }

                    $isPlaced = true;
                    $this->filmGroups[$film->id] = $this->filmGroup->make(
                        filmId: $film->id,
                        filmType: $film->filmType,
                        groups: [$group]
                    );

                    break;
                }

                $filmGroup = $this->filmGroups[$film->id];

                if ($filmGroup->getTotalLength() + $group->getLength() + $rollsLength <= $film->length) {
                    $filmGroup->addProductGroup($group);
                    $isPlaced = true;
                    break;
                }
            }

            if (!$isPlaced) {
                $this->handleNoAvailableFilms($group);
            }
        }

        return $this->optimizeGroups($this->filmGroups);
    }

    /**
     * Method to get the total length of manually created rolls by film ID.
     *
     * @param string $filmId The ID of the film
     *
     * @return float The total length of manually created rolls
     */
    private function getManuallyCreatedRollsLengthByFilmId(string $filmId): float
    {
        $rolls = $this->rollRepository->findByFilter(new RollFilter(process: Process::ORDER_CHECK_IN, filmIds: [$filmId]));
        $rollsLength = $rolls
                            ->map(fn (Roll $roll) => $roll->getPrintedProductsLength())
                            ->toArray();

        return array_sum($rollsLength);
    }

    /**
     * Handles the case when no available films are found for a specific film group.
     *
     * @param mixed $group The film group which does not have any available films
     */
    private function handleNoAvailableFilms($group): void
    {
        if (!isset($this->filmGroups[null])) {
            $this->filmGroups[null] = $this->filmGroup->make(null, null, [$group]);
        } else {
            $this->filmGroups[null]->addProductGroup($group);
        }
    }

    /**
     * Optimizes the groups and returns an array.
     *
     * @param FilmGroup[] $filmGroups
     *
     * @return FilmGroup[]
     */
    private function optimizeGroups(array $filmGroups): array
    {
        foreach ($filmGroups as $key => $filmGroup) {
            if (count($filmGroup->getGroups()) <= 1 || !$filmGroup->filmId) {
                continue;
            }

            $groups = new ArrayCollection($filmGroup->getGroups());
            $filmType = $groups->first()->filmType;
            $printer = $groups->first()->getPrinter();

            $sameFilmType = $groups->forAll(fn (int $index, ProductGroup $group) => $group->filmType === $filmType);
            $samePrinter = $groups->forAll(fn (int $index, ProductGroup $group) => $group->getPrinter()->getId() === $printer->getId());

            if ($sameFilmType && $samePrinter) {
                $products = array_merge(...$groups->map(fn (ProductGroup $group) => $group->getPrintedProducts())->toArray());
                $group = new ProductGroup('', $products, $filmType);
                $group->assignPrinter($printer);

                $filmGroups[$key] = $this->filmGroup->make(
                    filmId: $filmGroup->filmId,
                    filmType: $filmGroup->filmType,
                    groups: [$group]
                );
            }
        }

        return $filmGroups;
    }
}
