<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase;

use App\Orders\Application\UseCase\Command\AddRoll\AddRollCommand;
use App\Orders\Application\UseCase\Command\CreatePrinters\CreatePrintersCommand;
use App\Orders\Application\UseCase\Command\DeleteRoll\DeleteRollCommand;
use App\Orders\Application\UseCase\Command\UpdateRoll\UpdateRollCommand;
use App\Shared\Application\Command\CommandBusInterface;

/**
 * Class PrivateCommandInteractor.
 */
readonly class PrivateCommandInteractor
{
    /**
     * Class ExampleClass.
     */
    public function __construct(
        private CommandBusInterface $commandBus
    ) {
    }

    /**
     * Add a new roll to the database.
     *
     * @param string $name     the name of the roll
     * @param string $quality  the quality of the roll
     * @param string $rollType the type of the roll
     */
    public function addRoll(string $name, int $length, string $quality, string $rollType, ?int $priority = null, ?string $qualityNotes = null): int
    {
        $command = new AddRollCommand(
            name: $name, length: $length, quality: $quality, rollType: $rollType, qualityNotes: $qualityNotes, priority: $priority
        );

        return $this->commandBus->execute($command);
    }

    /**
     * Update a roll in the database.
     *
     * @param int         $id           the ID of the roll to update
     * @param string      $name         the name of the roll
     * @param string      $quality      the quality of the roll
     * @param string      $rollType     the type of the roll
     * @param int         $length       the length of the roll
     * @param int         $priority     the priority of the roll (optional, defaults to 0)
     * @param string|null $qualityNotes additional notes about the quality (optional)
     */
    public function updateRoll(int $id, string $name, string $quality, string $rollType, int $length, int $priority = 0, ?string $qualityNotes = null): void
    {
        $command = new UpdateRollCommand($id, $name, $quality, $rollType, $length, $priority, $qualityNotes);
        $this->commandBus->execute($command);
    }

    /**
     * Delete a roll from the database.
     *
     * @param int $rollId the ID of the roll to delete
     */
    public function deleteRoll(int $rollId): void
    {
        $command = new DeleteRollCommand($rollId);
        $this->commandBus->execute($command);
    }

    public function createPrinters(): void
    {
        $command = new CreatePrintersCommand();
        $this->commandBus->execute($command);
    }
}
