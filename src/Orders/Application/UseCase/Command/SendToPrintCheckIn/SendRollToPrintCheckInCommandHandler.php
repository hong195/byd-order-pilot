<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\SendToPrintCheckIn;

use App\Orders\Domain\Exceptions\NotEnoughFilmLengthToPrintTheRollException;
use App\Orders\Domain\Exceptions\PrinterIsNotAvailableException;
use App\Orders\Domain\Exceptions\RollCantBeSentPrintedException;
use App\Orders\Domain\Service\SendRollToPrintCheckInService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class SendRollToPrintCheckInCommandHandler.
 *
 * This class is responsible for handling the SendRollToPrintCheckIntCommand.
 */
readonly class SendRollToPrintCheckInCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param AccessControlService          $accessControlService      The access control service
     * @param SendRollToPrintCheckInService $sendToPrintCheckInService The send to print check in service
     */
    public function __construct(private AccessControlService $accessControlService, private SendRollToPrintCheckInService $sendToPrintCheckInService)
    {
    }

    /**
     * @throws RollCantBeSentPrintedException
     * @throws NotEnoughFilmLengthToPrintTheRollException
     * @throws PrinterIsNotAvailableException
     */
    public function __invoke(SendRollToPrintCheckIntCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $this->sendToPrintCheckInService->handle($command->rollId);
    }
}
