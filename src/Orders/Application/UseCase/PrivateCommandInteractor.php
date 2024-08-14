<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase;

use App\Orders\Application\UseCase\Command\ChangeOrderPriority\ChangeOrderPriorityCommand;
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
}
