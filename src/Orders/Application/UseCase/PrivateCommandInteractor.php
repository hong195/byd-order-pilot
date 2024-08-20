<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase;

use App\Orders\Application\DTO\SortOrderData;
use App\Orders\Application\UseCase\Command\AssignOrder\AssignOrderCommand;
use App\Orders\Application\UseCase\Command\ChangeOrderPriority\ChangeOrderPriorityCommand;
use App\Orders\Application\UseCase\Command\ChangeOrderSort\ChangeOrderSortCommand;
use App\Orders\Application\UseCase\Command\ChangePrinterAvailability\ChangePrinterAvailabilityCommand;
use App\Orders\Application\UseCase\Command\CreatePrinters\CreatePrintersCommand;
use App\Orders\Application\UseCase\Command\CuttingCheckIn\CuttingCheckIntCommand;
use App\Orders\Application\UseCase\Command\GlowCheckIn\GlowCheckInCommand;
use App\Orders\Application\UseCase\Command\PrintCheckIn\PrintCheckIntCommand;
use App\Orders\Application\UseCase\Command\UnassignOrder\UnassignOrderCommand;
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
    public function sendRollToPrintCheckIn(int $rollId): void
    {
        $this->commandBus->execute(new PrintCheckIntCommand($rollId));
    }

    /**
     * Sends a roll to the glow check-in process.
     *
     * @param int $rollId the ID of the roll to be sent
     */
    public function sendRollToGlowCheckIn(int $rollId): void
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
}
