<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase;

use App\Orders\Application\DTO\SortOrderData;
use App\Orders\Application\UseCase\Command\AssignOrder\AssignOrderCommand;
use App\Orders\Application\UseCase\Command\ChangeOrderPriority\ChangeOrderPriorityCommand;
use App\Orders\Application\UseCase\Command\ChangeOrderSort\ChangeOrderSortCommand;
use App\Orders\Application\UseCase\Command\CreatePrinters\CreatePrintersCommand;
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
}
