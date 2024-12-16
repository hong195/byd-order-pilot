<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CuttingCheckIn;

use App\ProductionProcess\Domain\Exceptions\RollCantBeSentToCuttingException;
use App\ProductionProcess\Domain\Service\Roll\CuttingCheckInService;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * Class CuttingCheckInCommandHandler.
 *
 * This class is responsible for handling the SendRollToPrintCheckIntCommand.
 */
readonly class CuttingCheckInCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     */
    public function __construct(private CuttingCheckInService $cuttingCheckInService)
    {
    }

    /**
     * Invokes the command handler.
     *
     * @param CuttingCheckIntCommand $command The cutting check in command
     *
     * @throws RollCantBeSentToCuttingException
     */
    public function __invoke(CuttingCheckIntCommand $command): void
    {
        $this->cuttingCheckInService->handle($command->rollId);
    }
}
