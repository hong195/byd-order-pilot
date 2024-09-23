<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CuttingCheckIn;

use App\ProductionProcess\Domain\Exceptions\RollCantBeSentToCuttingException;
use App\ProductionProcess\Domain\Service\Roll\CuttingCheckInService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class CuttingCheckInCommandHandler.
 *
 * This class is responsible for handling the SendRollToPrintCheckIntCommand.
 */
readonly class CuttingCheckInCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param AccessControlService $accessControlService The access control service
     */
    public function __construct(private AccessControlService $accessControlService, private CuttingCheckInService $cuttingCheckInService)
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
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $this->cuttingCheckInService->handle($command->rollId);
    }
}
