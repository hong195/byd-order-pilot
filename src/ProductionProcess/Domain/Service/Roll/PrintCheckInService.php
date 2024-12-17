<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Exceptions\InventoryFilmIsNotAvailableException;
use App\ProductionProcess\Domain\Exceptions\NotEnoughFilmLengthToPrintTheRollException;
use App\ProductionProcess\Domain\Exceptions\PrinterIsNotAvailableException;
use App\ProductionProcess\Domain\Exceptions\RollCantBeSentToPrintException;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Exception\DomainException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

/**
 * This class is responsible for handling the printing process.
 */
final readonly class PrintCheckInService
{
    /**
     * Constructor for the Symfony application.
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private AvailableFilmServiceInterface $availableFilmService, private GeneralProcessValidation $generalProcessValidatior)
    {
    }

    /**
     * Print a roll.
     *
     * @param string $rollId The ID of the roll to be printed
     *
     * @throws NotFoundHttpException                      If the roll is not found
     * @throws PrinterIsNotAvailableException             If the printer is not available
     * @throws RollCantBeSentToPrintException             If the roll is not in the correct process
     * @throws NotEnoughFilmLengthToPrintTheRollException
     * @throws InventoryFilmIsNotAvailableException
     * @throws ExceptionInterface
     * @throws DomainException
     */
    public function handle(string $rollId): void
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

        $roll->sendPrintCheckIn();

        $this->rollRepository->save($roll);
    }

    /**
     * Check if a film is currently in usage in a roll.
     *
     * @param string $filmId the ID of the film to check
     * @param string $rollId the ID of the roll to exclude from the check
     *
     * @return bool returns true if the film is in usage in any other roll except the excluded one, false otherwise
     */
    private function isFilmInUsage(string $filmId, string $rollId): bool
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
