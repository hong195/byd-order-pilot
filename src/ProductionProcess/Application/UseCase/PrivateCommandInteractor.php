<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase;

use App\ProductionProcess\Application\UseCase\Command\AssignEmployeeToRoll\AssignEmployeeToRollCommand;
use App\ProductionProcess\Application\UseCase\Command\ChangePrinterAvailability\ChangePrinterAvailabilityCommand;
use App\ProductionProcess\Application\UseCase\Command\CreatePrinters\CreatePrintersCommand;
use App\ProductionProcess\Application\UseCase\Command\CuttingCheckIn\CuttingCheckIntCommand;
use App\ProductionProcess\Application\UseCase\Command\GlowCheckIn\GlowCheckInCommand;
use App\ProductionProcess\Application\UseCase\Command\PrintCheckIn\PrintCheckIntCommand;
use App\ProductionProcess\Application\UseCase\Command\RecordRollHistory\RecordRollHistoryCommand;
use App\ProductionProcess\Application\UseCase\Command\ReprintRoll\ReprintRollCommand;
use App\ProductionProcess\Application\UseCase\Command\UnAssignEmployeeFromRoll\UnAssignEmployeeFromRollCommand;
use App\Shared\Application\Command\CommandBusInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PrivateCommandInteractor.
 */
readonly class PrivateCommandInteractor
{
    /**
     * Class ExampleClass.
     */
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    /**
     * Create printers in the database.
     */
    public function createPrinters(): void
    {
        $command = new CreatePrintersCommand();
        $this->commandBus->execute($command);
    }

    /**
     * Makes a printer available.
     *
     * @param int $printerId The ID of the printer
     */
    public function makePrinterAvailable(int $printerId): void
    {
        $command = new ChangePrinterAvailabilityCommand(printerId: $printerId, isAvailable: true);
        $this->commandBus->execute($command);
    }

    /**
     * Makes a printer available.
     *
     * @param int $printerId The ID of the printer
     */
    public function makePrinterUnAvailable(int $printerId): void
    {
        $command = new ChangePrinterAvailabilityCommand(printerId: $printerId, isAvailable: false);
        $this->commandBus->execute($command);
    }

    /**
     * Sends a roll to print check-in.
     *
     * @param int $rollId The ID of the roll to send to print check-in
     */
    public function printingCheckIn(int $rollId): void
    {
        $this->commandBus->execute(new PrintCheckIntCommand($rollId));
    }

    /**
     * Sends a roll to the glow check-in process.
     *
     * @param int $rollId the ID of the roll to be sent
     */
    public function glowCheckIn(int $rollId): void
    {
        $this->commandBus->execute(new GlowCheckInCommand($rollId));
    }

    /**
     * Sends a roll to be checked in for cutting.
     *
     * @param int $rollId The ID of the roll to be checked in
     */
    public function cuttingCheckIn(int $rollId): void
    {
        $this->commandBus->execute(new CuttingCheckIntCommand($rollId));
    }

    /**
     * Prints a new copy of an order.
     *
     * @param int $rollId The ID of the roll
     *
     * @throws NotFoundHttpException
     */
    public function reprintRoll(int $rollId): void
    {
        $this->commandBus->execute(new ReprintRollCommand($rollId));
    }

    /**
     * Assigns an employee to a role.
     *
     * @param int $rollId     The ID of the role
     * @param int $employeeId The ID of the employee
     */
    public function assignEmployeeToARoll(int $rollId, int $employeeId): void
    {
        $this->commandBus->execute(new AssignEmployeeToRollCommand(rollId: $rollId, employeeId: $employeeId));
    }

    /**
     * Unassigns an employee from a roll.
     *
     * @param int $rollId The ID of the roll
     */
    public function unassignEmployeeFromRoll(int $rollId): void
    {
        $this->commandBus->execute(new UnAssignEmployeeFromRollCommand(rollId: $rollId));
    }

    /**
     * Synchronizes the roll history.
     *
     * @param int $rollId The ID of the roll
     */
    public function recoredRollProcessUpdate(int $rollId): void
    {
        $this->commandBus->execute(new RecordRollHistoryCommand($rollId));
    }
}
