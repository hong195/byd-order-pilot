<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase;

use App\Orders\Application\UseCase\Command\AddLamination\AddLaminationCommand;
use App\Orders\Application\UseCase\Command\AddRoll\AddRollCommand;
use App\Orders\Application\UseCase\Command\DeleteLamination\DeleteLaminationCommand;
use App\Orders\Application\UseCase\Command\DeleteRoll\DeleteRollCommand;
use App\Orders\Application\UseCase\Command\UpdateLamination\UpdateLaminationCommand;
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
     * Add a lamination to the database.
     *
     * @param string $name           the name of the lamination
     * @param string $quality        the quality of the lamination
     * @param string $laminationType the type of lamination
     */
    public function addLamination(string $name, string $quality, string $laminationType): void
    {
        $command = new AddLaminationCommand($name, $quality, $laminationType);
        $this->commandBus->execute($command);
    }

    /**
     * Updates the lamination with the specified details.
     *
     * @param int         $id             the ID of the lamination
     * @param string      $name           the name of the lamination
     * @param string      $quality        the quality of the lamination
     * @param string      $laminationType the type of lamination
     * @param int         $length         the length of the lamination
     * @param int         $priority       The priority of the lamination. Default is 0.
     * @param string|null $qualityNotes   Additional notes about the quality. Default is null.
     */
    public function updateLamination(
        int $id,
        string $name,
        string $quality,
        string $laminationType,
        int $length,
        int $priority = 0,
        ?string $qualityNotes = null
    ): void {
        $command = new UpdateLaminationCommand($id, $name, $quality, $laminationType, $length, $priority, $qualityNotes);
        $this->commandBus->execute($command);
    }

    /**
     * Add a new roll to the database.
     *
     * @param string $name     the name of the roll
     * @param string $quality  the quality of the roll
     * @param string $rollType the type of the roll
     */
    public function addRoll(string $name, string $quality, string $rollType): int
    {
        $command = new AddRollCommand($name, $quality, $rollType);

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
    public function updateRoll(
        int $id,
        string $name,
        string $quality,
        string $rollType,
        int $length,
        int $priority = 0,
        ?string $qualityNotes = null
    ): void {
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

    /**
     * Delete a lamination from the database.
     *
     * @param int $laminationId the ID of the lamination to delete
     */
    public function deleteLamination(int $laminationId): void
    {
        $command = new DeleteLaminationCommand($laminationId);
        $this->commandBus->execute($command);
    }
}
