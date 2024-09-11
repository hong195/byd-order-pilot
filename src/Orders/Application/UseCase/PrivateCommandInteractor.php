<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase;

use App\Orders\Application\DTO\SortOrderData;
use App\Orders\Application\UseCase\Command\AssignEmployeeToRoll\AssignEmployeeToRollCommand;
use App\Orders\Application\UseCase\Command\AssignOrder\AssignOrderCommand;
use App\Orders\Application\UseCase\Command\ChangeOrderPriority\ChangeOrderPriorityCommand;
use App\Orders\Application\UseCase\Command\ChangeOrderSort\ChangeOrderSortCommand;
use App\Orders\Application\UseCase\Command\ChangePrinterAvailability\ChangePrinterAvailabilityCommand;
use App\Orders\Application\UseCase\Command\CopyRollHistory\CopyRollHistoryCommand;
use App\Orders\Application\UseCase\Command\CreateExtra\CreateExtraCommand;
use App\Orders\Application\UseCase\Command\CreatePrinters\CreatePrintersCommand;
use App\Orders\Application\UseCase\Command\CuttingCheckIn\CuttingCheckIntCommand;
use App\Orders\Application\UseCase\Command\GlowCheckIn\GlowCheckInCommand;
use App\Orders\Application\UseCase\Command\PackExtra\PackExtraCommand;
use App\Orders\Application\UseCase\Command\PackMainProduct\PackMainProductCommand;
use App\Orders\Application\UseCase\Command\PrintCheckIn\PrintCheckIntCommand;
use App\Orders\Application\UseCase\Command\RecordRollHistory\RecordRollHistoryCommand;
use App\Orders\Application\UseCase\Command\ReprintOrder\ReprintOrderCommand;
use App\Orders\Application\UseCase\Command\ReprintRoll\ReprintRollCommand;
use App\Orders\Application\UseCase\Command\ShipAndCollectOrders\ShipAndCollectOrdersCommand;
use App\Orders\Application\UseCase\Command\UnAssignEmployeeFromRoll\UnAssignEmployeeFromRollCommand;
use App\Orders\Application\UseCase\Command\UnassignOrder\UnassignOrderCommand;
use App\Orders\Application\UseCase\Command\UnPackExtra\UnPackExtraCommand;
use App\Orders\Application\UseCase\Command\UnPackMainProduct\UnPackMainProductCommand;
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
     * Changes the status of an order.
     *
     * @param bool $status The new status of the order
     */
    public function changeOrderPriority(int $id, bool $status): void
    {
        $this->commandBus->execute(new ChangeOrderPriorityCommand($id, $status));
    }

    /**
     * Unassigns an order.
     *
     * @param int $id The id of the order to unassign
     */
    public function unassignOrder(int $id): void
    {
        $this->commandBus->execute(new UnassignOrderCommand($id));
    }

    /**
     * Assigns an order. Triggers the check-in process.
     *
     * @param int $id The id of the order to assign
     */
    public function assignOrder(int $id): void
    {
        $this->commandBus->execute(new AssignOrderCommand($id));
    }

    /**
     * Changes the sort order of an order.
     *
     * @param SortOrderData $orderData The data containing the roll ID, order ID, and sort order
     */
    public function changeSortOrder(SortOrderData $orderData): void
    {
        $command = new ChangeOrderSortCommand(rollId: $orderData->rollId, group: $orderData->group, sortOrders: $orderData->sortOrders);
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
     * Ships and collects orders for a given roll.
     *
     * @param int $rollId The ID of the roll
     */
    public function shipAndCollectOrders(int $rollId): void
    {
        $this->commandBus->execute(new ShipAndCollectOrdersCommand($rollId));
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
     * Prints a new copy of an order.
     *
     * @param int $orderId The ID of the order
     *
     * @throws NotFoundHttpException
     */
    public function reprintOrder(int $orderId): void
    {
        $this->commandBus->execute(new ReprintOrderCommand($orderId));
    }

    /**
     * Creates an extra.
     *
     * @param CreateExtraCommand $command The command to create an extra
     */
    public function createExtra(CreateExtraCommand $command): void
    {
        $this->commandBus->execute($command);
    }

    /**
     * Packs the main product of an order.
     *
     * @param int $rollId  The ID of the roll to be packed
     * @param int $orderId The ID of the order to which the roll belongs
     */
    public function packMainProduct(int $rollId, int $orderId): void
    {
        $this->commandBus->execute(new PackMainProductCommand($rollId, $orderId));
    }

    /**
     * Unpacks the main product from a roll for an order.
     *
     * @param int $rollId  The ID of the roll containing the main product
     * @param int $orderId The ID of the order
     */
    public function unpackMainProduct(int $rollId, int $orderId): void
    {
        $this->commandBus->execute(new UnPackMainProductCommand($rollId, $orderId));
    }

    /**
     * Packs or unpacks an extra for an order.
     *
     * @param int $orderId The ID of the order
     * @param int $extraId The ID of the extra
     */
    public function packExtra(int $orderId, int $extraId): void
    {
        $this->commandBus->execute(new PackExtraCommand(orderId: $orderId, extraId: $extraId));
    }

    /**
     * Unpacks an extra for a given order.
     *
     * @param int $orderId The ID of the order
     * @param int $extraId The ID of the extra to unpack
     */
    public function unPackExtra(int $orderId, int $extraId): void
    {
        $this->commandBus->execute(new UnPackExtraCommand(orderId: $orderId, extraId: $extraId));
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
    public function recordRollHistory(int $rollId): void
    {
        $this->commandBus->execute(new RecordRollHistoryCommand($rollId));
    }

    /**
     * Copies the history of a roll to another roll.
     *
     * @param int   $rollId  The ID of the roll
     * @param int[] $rollIds The IDs of the rolls to which the history will be copied
     */
    public function copyRollHistory(int $rollId, array $rollIds): void
    {
        $command = new CopyRollHistoryCommand(rollId: $rollId, newRollIds: $rollIds);
        $this->commandBus->execute($command);
    }
}
