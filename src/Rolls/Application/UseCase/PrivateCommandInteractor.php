<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase;

use App\Rolls\Application\UseCase\Command\UpdateLamination\UpdateLaminationCommand;
use App\Shared\Application\Command\CommandBusInterface;

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
    public function updateLamination(int $id, string $name, string $quality, string $laminationType, int $length, int $priority = 0, ?string $qualityNotes = null): void
    {
        $command = new UpdateLaminationCommand($id, $name, $quality, $laminationType, $length, $priority, $qualityNotes);
        $this->commandBus->execute($command);
    }
}
