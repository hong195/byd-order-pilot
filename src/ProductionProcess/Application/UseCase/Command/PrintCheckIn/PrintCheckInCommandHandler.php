<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\PrintCheckIn;

use App\ProductionProcess\Domain\Exceptions\NotEnoughFilmLengthToPrintTheRollException;
use App\ProductionProcess\Domain\Exceptions\PrinterIsNotAvailableException;
use App\ProductionProcess\Domain\Exceptions\RollCantBeSentToPrintException;
use App\ProductionProcess\Domain\Service\Roll\PrintCheckInService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class PrintCheckInCommandHandler.
 *
 * This class is responsible for handling the PrintCheckIntCommand.
 */
readonly class PrintCheckInCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param AccessControlService $accessControlService      The access control service
     * @param PrintCheckInService  $sendToPrintCheckInService The send to print check in service
     */
    public function __construct(private AccessControlService $accessControlService, private PrintCheckInService $sendToPrintCheckInService)
    {
    }

    /**
     * @throws RollCantBeSentToPrintException
     * @throws NotEnoughFilmLengthToPrintTheRollException
     * @throws PrinterIsNotAvailableException
     */
    public function __invoke(PrintCheckIntCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $this->sendToPrintCheckInService->handle($command->rollId);
    }
}
