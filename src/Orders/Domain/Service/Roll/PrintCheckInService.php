<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Roll;

use App\Orders\Domain\DTO\FilmData;
use App\Orders\Domain\Events\RollWasSentToPrintCheckInEvent;
use App\Orders\Domain\Exceptions\NotEnoughFilmLengthToPrintTheRollException;
use App\Orders\Domain\Exceptions\PrinterIsNotAvailableException;
use App\Orders\Domain\Exceptions\RollCantBeSentToPrintException;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Orders\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\Orders\Domain\ValueObject\Process;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    public function __construct(private RollRepositoryInterface $rollRepository, private AvailableFilmServiceInterface $availableFilmService, private EventDispatcherInterface $eventDispatcher, private GeneralProcessValidation $generalProcessValidatior)
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

        $availableFilm = $this->getByFilmType($roll->getFilmId());

        if (!$availableFilm || $availableFilm->length < $roll->getOrdersLength()) {
            throw new NotEnoughFilmLengthToPrintTheRollException('Not enough film to print');
        }

        $roll->updateProcess(Process::PRINTING_CHECK_IN);
        $this->rollRepository->save($roll);

        $this->eventDispatcher->dispatch(new RollWasSentToPrintCheckInEvent($roll->getId()));
    }

    /**
     * Retrieves a film by its type.
     *
     * If a specific film ID is provided, it filters the available film list
     * and returns the first film matching the ID. If no film ID is provided,
     * it returns null.
     *
     * @param int|null $filmId The ID of the film to retrieve. (optional)
     *
     * @return FilmData|null the film object matching the provided ID, or null if not found
     */
    private function getByFilmType(?int $filmId = null): ?FilmData
    {
        $result = $this->availableFilmService->getAvailableFilms()->filter(function (FilmData $filmData) use ($filmId) {
            return $filmData->id === $filmId;
        })
            ->first();

        return $result ?: null;
    }
}
