<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Events\RollWasSentToPrintCheckInEvent;
use App\ProductionProcess\Domain\Exceptions\InventoryFilmIsNotAvailableException;
use App\ProductionProcess\Domain\Exceptions\NotEnoughFilmLengthToPrintTheRollException;
use App\ProductionProcess\Domain\Exceptions\PrinterIsNotAvailableException;
use App\ProductionProcess\Domain\Exceptions\RollCantBeSentToPrintException;
use  App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Exception\DomainException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * This class is responsible for handling the printing process.
 */
final readonly class PrintCheckInService
{
    /**
     * Construct the class.
     *
     * @param RollRepositoryInterface       $rollRepository       the roll repository
     * @param AvailableFilmServiceInterface $availableFilmService the available film service
     * @param EventDispatcherInterface      $eventDispatcher      the event dispatcher
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private AvailableFilmServiceInterface $availableFilmService, private MessageBusInterface $eventDispatcher, private GeneralProcessValidation $generalProcessValidatior)
    {
    }

	/**
	 * Print a roll.
	 *
	 * @param int $rollId The ID of the roll to be printed
	 *
	 * @throws NotFoundHttpException                      If the roll is not found
	 * @throws PrinterIsNotAvailableException             If the printer is not available
	 * @throws RollCantBeSentToPrintException             If the roll is not in the correct process
	 * @throws NotEnoughFilmLengthToPrintTheRollException
	 * @throws InventoryFilmIsNotAvailableException
	 * @throws ExceptionInterface
	 * @throws DomainException
	 */
    public function handle(int $rollId): void
    {
        $roll = $this->rollRepository->findById($rollId);

        $this->generalProcessValidatior->validate($roll);

        if (!$roll->getProcess()->equals(Process::ORDER_CHECK_IN)) {
            throw new RollCantBeSentToPrintException('Roll cannot be printed! It is not in the correct process.');
        }

        $printer = $roll->getPrinter();

        if (!$printer->isAvailable()) {
            throw new PrinterIsNotAvailableException('Printer is not available');
        }

        $availableFilm = $this->availableFilmService->getByFilmId($roll->getFilmId());

        if (!$availableFilm || $availableFilm->length < $roll->getPrintedProductsLength()) {
            throw new NotEnoughFilmLengthToPrintTheRollException('Not enough film to print');
        }

        if ($this->isFilmInUsage($roll->getFilmId(), $rollId)) {
            InventoryFilmIsNotAvailableException::because('Current film is already in use');
        }

		$roll->printCheckIn();

        $this->rollRepository->save($roll);
	}

    private function isFilmInUsage(int $filmId, int $rollId): bool
    {
        $rollsWithFilm = $this->rollRepository->findByFilmId($filmId)
            ->filter(function (Roll $roll) use ($rollId) {
                return $roll->getId() !== $rollId;
            })
            ->filter(function (Roll $roll) {
                return $roll->getProcess()->equals(Process::PRINTING_CHECK_IN);
            })
        ;

        return !$rollsWithFilm->isEmpty();
    }
}
