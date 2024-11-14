<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\DTO\FilmData;
use App\ProductionProcess\Domain\Exceptions\ManualArrangeException;
use App\ProductionProcess\Domain\Repository\PrintedProductFilter;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollFilter;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\FilmAssignmentService;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\FilmAvailabilityValidator;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Groups\FilmGroup;
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
        private FilmAvailabilityValidator $filmAvailabilityValidator,
        private PrinterValidator $printerValidator,
        private FilmTypeValidator $filmValidator,
		private AvailableFilmServiceInterface $availableFilmService
    ) {
    }

    /**
     * @throws ManualArrangeException
	 * @throws DomainException
	 */
    public function arrange(array $printedProductIds = []): void
    {
        $printedProducts = $this->printedProductRepository->findByFilter(new PrintedProductFilter(ids: $printedProductIds));

        $this->filmValidator->validate($printedProducts);
        $this->printerValidator->validate($printedProducts);
        $this->filmAvailabilityValidator->validate($printedProducts);

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
        $length = array_sum($printedProducts->map(fn (PrintedProduct $pp) => $pp->getLength())->toArray());
		$filmType = $printedProducts->first()->getFilmType();

        $availableFilms = $this->getAvailableFilms(filmType: $filmType, minLength:  $length);
		$rollInArrangeProcess = $this->fetchRolls($availableFilms->map(fn (FilmData $film) => $film->id)->toArray());

		if (!$availableFilms->isEmpty()) {
			foreach ($availableFilms as $film) {
				$roll = $rollInArrangeProcess->filter(fn (Roll $roll) => $roll->getFilmId() === $film->id)->first();



			}
		}


		$totalLength = $filmGroup->getTotalLength();

		if (!$rollInArrangeProcess->isEmpty()) {
			$totalLength += array_sum(array_map(fn (Roll $roll) => $roll->getTotalLength(), $rollInArrangeProcess->toArray()));
		}

		if ($film->length < $totalLength) {
			ManualArrangeException::because('No enough film to manually arrange the printed products');
		}


        return $filmGroups[0];
    }

	/**
	 *
	 */
	private function fetchRolls(array $ids): Collection
	{
		return $this->rollRepository->findByFilter(new RollFilter(process: Process::ORDER_CHECK_IN, filmIds: [$ids]));
	}
	/**
	 * Retrieves an available film based on the given film type and minimum length.
	 *
	 * @param string $filmType  The type of the film to search for
	 * @param float  $minLength The minimum length required for the film
	 *
	 * @return Collection<FilmData> The available FilmData object matching the criteria, or null if none found
	 */
	private function getAvailableFilms(string $filmType, float $minLength): Collection
	{
		$films = $this->availableFilmService->getAvailableFilms();

		return $films->filter(function (FilmData $film) use ($filmType, $minLength) {
			return $film->filmType === $filmType && $film->length >= $minLength;
		});
	}
}
