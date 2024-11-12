<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Exceptions\ManualArrangeException;
use App\ProductionProcess\Domain\Repository\RollFilter;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Groups\FilmGroup;
use App\ProductionProcess\Domain\ValueObject\Process;

final readonly class FilmAvailabilityValidator
{
    /**
     * Constructor method for the ManualProductsArrangeService class.
     *
     * @param RollRepositoryInterface $rollRepository The Roll Repository Interface being injected
     */
    public function __construct(
        private RollRepositoryInterface $rollRepository,
        private AvailableFilmServiceInterface $availableFilmService,
    ) {
    }

    /**
     * Method to check if there is enough film to arrange the printed products manually.
     *
     * @param FilmGroup $filmGroup The FilmGroup object containing film information
     *
     * @throws ManualArrangeException if there is not enough film available for arranging the printed products
     */
    public function validate(FilmGroup $filmGroup): void
    {
        if (!$filmGroup->filmId) {
            ManualArrangeException::because('Not found film');
        }

        $rollInArrangeProcess = $this->rollRepository->findByFilter(new RollFilter(process: Process::ORDER_CHECK_IN, filmIds: [$filmGroup->filmId]));
        $film = $this->availableFilmService->getByFilmId($filmGroup->filmId);

        $totalLength = $filmGroup->getTotalLength();

        if (!$rollInArrangeProcess->isEmpty()) {
            $totalLength += array_sum(array_map(fn (Roll $roll) => $roll->getTotalLength(), $rollInArrangeProcess->toArray()));
        }

        if ($film->length < $totalLength) {
            ManualArrangeException::because('No enough film to manually arrange the printed products');
        }
    }
}
