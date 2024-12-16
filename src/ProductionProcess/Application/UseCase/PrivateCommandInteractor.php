<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase;

use App\Orders\Application\DTO\Order\SortData;
use App\ProductionProcess\Application\UseCase\Command\AssignEmployeeToRoll\AssignEmployeeToRollCommand;
use App\ProductionProcess\Application\UseCase\Command\ChangePrintedProductPriority\ChangePrintedProductPriorityCommand;
use App\ProductionProcess\Application\UseCase\Command\ChangePrintedProductSort\ChangePrintedProductSortCommand;
use App\ProductionProcess\Application\UseCase\Command\ChangePrinterAvailability\ChangePrinterAvailabilityCommand;
use App\ProductionProcess\Application\UseCase\Command\CheckInPrintedProducts\CheckInPrintedProductsCommand;
use App\ProductionProcess\Application\UseCase\Command\CheckInPrintedProducts\CheckInPrintedProductsCommandResult;
use App\ProductionProcess\Application\UseCase\Command\CheckRemainingProducts\CheckRemainingProductsCommand;
use App\ProductionProcess\Application\UseCase\Command\CreatePrintedProduct\CreatePrintedProductCommand;
use App\ProductionProcess\Application\UseCase\Command\CreatePrinters\CreatePrintersCommand;
use App\ProductionProcess\Application\UseCase\Command\CuttingCheckIn\CuttingCheckIntCommand;
use App\ProductionProcess\Application\UseCase\Command\GlowCheckIn\GlowCheckInCommand;
use App\ProductionProcess\Application\UseCase\Command\LockRoll\LockRollCommand;
use App\ProductionProcess\Application\UseCase\Command\ManualProductsArrangement\ManualProductsArrangementQuery;
use App\ProductionProcess\Application\UseCase\Command\MergeRolls\MergeRollsCommand;
use App\ProductionProcess\Application\UseCase\Command\PrintCheckIn\PrintCheckIntCommand;
use App\ProductionProcess\Application\UseCase\Command\RecordRollHistory\RecordRollHistoryCommand;
use App\ProductionProcess\Application\UseCase\Command\ReprintPrintedProduct\ReprintPrintedProductCommand;
use App\ProductionProcess\Application\UseCase\Command\ReprintRoll\ReprintRollCommand;
use App\ProductionProcess\Application\UseCase\Command\TakePhoto\TakePhotoCommand;
use App\ProductionProcess\Application\UseCase\Command\UnAssignEmployeeFromRoll\UnAssignEmployeeFromRollCommand;
use App\ProductionProcess\Application\UseCase\Command\UnassignPrintedProduct\UnassignPrintedProductCommand;
use App\ProductionProcess\Application\UseCase\Command\UnLockRoll\UnLockRollCommand;
use App\ProductionProcess\Domain\Exceptions\UnassignedPrintedProductsException;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Application\Command\CommandBusInterface;

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
     * @param string $printerId The ID of the printer
     */
    public function makePrinterAvailable(string $printerId): void
    {
        $command = new ChangePrinterAvailabilityCommand(printerId: $printerId, isAvailable: true);
        $this->commandBus->execute($command);
    }

    /**
     * Makes a printer available.
     *
     * @param string $printerId The ID of the printer
     */
    public function makePrinterUnAvailable(string $printerId): void
    {
        $command = new ChangePrinterAvailabilityCommand(printerId: $printerId, isAvailable: false);
        $this->commandBus->execute($command);
    }

    /**
     * Sends a roll to print check-in.
     *
     * @param string $rollId The ID of the roll to send to print check-in
     */
    public function printingCheckIn(string $rollId): void
    {
        $this->commandBus->execute(new PrintCheckIntCommand($rollId));
    }

    /**
     * Sends a roll to the glow check-in process.
     *
     * @param string $rollId the ID of the roll to be sent
     */
    public function glowCheckIn(string $rollId): void
    {
        $this->commandBus->execute(new GlowCheckInCommand($rollId));
    }

    /**
     * Sends a roll to be checked in for cutting.
     *
     * @param string $rollId The ID of the roll to be checked in
     */
    public function cuttingCheckIn(string $rollId): void
    {
        $this->commandBus->execute(new CuttingCheckIntCommand($rollId));
    }

    /**
     * Assigns an employee to a role.
     *
     * @param string $rollId     The ID of the role
     * @param string $employeeId The ID of the employee
     */
    public function assignEmployeeToARoll(string $rollId, string $employeeId): void
    {
        $this->commandBus->execute(new AssignEmployeeToRollCommand(rollId: $rollId, employeeId: $employeeId));
    }

    /**
     * Checks in printed products based on the provided printed product IDs.
     *
     * @param array $printedProducts An array containing the IDs of printed products to check in
     *
     * @return CheckInPrintedProductsCommandResult The result of the check-in operation
     */
    public function checkInPrintedProducts(array $printedProducts = []): CheckInPrintedProductsCommandResult
    {
        return $this->commandBus->execute(new CheckInPrintedProductsCommand(printedProductIds: $printedProducts));
    }

    /**
     * Unassigns an employee from a roll.
     *
     * @param string $rollId The ID of the roll
     */
    public function unassignEmployeeFromRoll(string $rollId): void
    {
        $this->commandBus->execute(new UnAssignEmployeeFromRollCommand(rollId: $rollId));
    }

    /**
     * Synchronizes the roll history.
     *
     * @param string $rollId The ID of the roll
     */
    public function recoredRollProcessUpdate(string $rollId, string $process): void
    {
        $this->commandBus->execute(new RecordRollHistoryCommand(rollId: $rollId, process: Process::from($process)));
    }

    /**
     * Creates a new printed product.
     *
     * @param CreatePrintedProductCommand $command the command containing the details of the printed product
     */
    public function createPrintedProduct(CreatePrintedProductCommand $command): void
    {
        $this->commandBus->execute($command);
    }

    /**
     * Changes the status of an order.
     *
     * @param bool $status The new status of the order
     */
    public function changePrintedProductPriority(string $id, bool $status): void
    {
        $this->commandBus->execute(new ChangePrintedProductPriorityCommand($id, $status));
    }

    /**
     * Unassigns an order.
     *
     * @param string $id The id of the order to unassign
     */
    public function unassignPrintedProduct(string $id): void
    {
        $this->commandBus->execute(new UnassignPrintedProductCommand($id));
    }

    /**
     * Reprints a printed product.
     */
    public function reprintPrintedProduct(ReprintPrintedProductCommand $command): void
    {
        $this->commandBus->execute($command);
    }

    /**
     * Changes the sort order of an order.
     *
     * @param SortData $orderData The data containing the roll ID, order ID, and sort order
     */
    public function changeSortOrder(SortData $orderData): void
    {
        $command = new ChangePrintedProductSortCommand(rollId: $orderData->rollId, group: $orderData->group, sortOrders: $orderData->sortOrders);
        $this->commandBus->execute($command);
    }

    /**
     * Checks the remaining products for a given roll.
     *
     * @param string $rollId The ID of the roll to check remaining products for
     */
    public function checkRemainingProducts(string $rollId): void
    {
        $this->commandBus->execute(new CheckRemainingProductsCommand($rollId));
    }

    /**
     * Locks a roll in the system.
     *
     * @param string $rollId The ID of the roll to be locked
     */
    public function lockRoll(string $rollId): void
    {
        $this->commandBus->execute(new LockRollCommand($rollId));
    }

    /**
     * Unlocks a roll.
     *
     * @param string $rollId The ID of the roll to unlock
     */
    public function unLockRoll(string $rollId): void
    {
        $this->commandBus->execute(new UnLockRollCommand($rollId));
    }

    /**
     * Manually arranges the printed products based on given IDs.
     *
     * @param int[] $printedProductsIds An array of IDs of the printed products to be arranged manually
     */
    public function manualArrangement(array $printedProductsIds): void
    {
        $this->commandBus->execute(new ManualProductsArrangementQuery($printedProductsIds));
    }

    /**
     * Executes the take photo command.
     *
     * @param TakePhotoCommand $command The command to take a photo
     *
     * @return string|null The url of stored product photo
     */
    public function takePhoto(TakePhotoCommand $command): ?string
    {
        return $this->commandBus->execute($command);
    }

    /**
     * Merges the specified rolls by executing a MergeRollsCommand through the command bus.
     *
     * @param int[] $rollsIds The array of roll IDs to be merged
     */
    public function mergeRolls(array $rollsIds): void
    {
        $this->commandBus->execute(new MergeRollsCommand($rollsIds));
    }
}
