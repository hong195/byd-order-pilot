<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\ChangePrinterAvailability;

use App\Orders\Domain\Repository\PrinterRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * Class ChangePrinterAvailabilityCommandHandler.
 */
readonly class ChangePrinterAvailabilityCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructor.
     *
     * @param PrinterRepositoryInterface $printerRepository the printer repository
     */
    public function __construct(private PrinterRepositoryInterface $printerRepository)
    {
    }

    /**
     * Invoke the change printer availability command.
     *
     * @param ChangePrinterAvailabilityCommand $changePrinterAvailabilityCommand the change printer availability command
     */
    public function __invoke(ChangePrinterAvailabilityCommand $changePrinterAvailabilityCommand): void
    {
        $printer = $this->printerRepository->findById($changePrinterAvailabilityCommand->printerId);

        $printer->changeAvailability($changePrinterAvailabilityCommand->isAvailable);

        $this->printerRepository->save($printer);
    }
}
