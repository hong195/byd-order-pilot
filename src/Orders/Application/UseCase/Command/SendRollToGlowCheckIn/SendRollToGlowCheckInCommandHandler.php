<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\SendRollToGlowCheckIn;

use App\Orders\Domain\Exceptions\RollCantBeSentToGlowException;
use App\Orders\Domain\Service\SendRollToGlowCheckInService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class SendRollToGlowCheckInCommandHandler.
 *
 * This class is responsible for handling the SendGlowToPrintCheckIntCommand.
 */
readonly class SendRollToGlowCheckInCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService         $accessControlService         the access control service
     * @param SendRollToGlowCheckInService $sendRollToGlowCheckInService the send roll to Glow CheckIn service
     */
    public function __construct(private AccessControlService $accessControlService, private SendRollToGlowCheckInService $sendRollToGlowCheckInService)
    {
    }

    /**
     * @throws NotFoundHttpException
     * @throws RollCantBeSentToGlowException
     */
    public function __invoke(SendRollToGlowCheckInCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $this->sendRollToGlowCheckInService->handle($command->rollId);
    }
}
