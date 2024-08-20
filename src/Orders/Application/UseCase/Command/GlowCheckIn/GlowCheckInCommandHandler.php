<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\GlowCheckIn;

use App\Orders\Domain\Exceptions\RollCantBeSentToGlowException;
use App\Orders\Domain\Service\GlowCheckInService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class GlowCheckInCommandHandler.
 *
 * This class is responsible for handling the SendGlowToPrintCheckIntCommand.
 */
readonly class GlowCheckInCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService $accessControlService         the access control service
     * @param GlowCheckInService   $sendRollToGlowCheckInService the send roll to Glow CheckIn service
     */
    public function __construct(private AccessControlService $accessControlService, private GlowCheckInService $sendRollToGlowCheckInService)
    {
    }

    /**
     * @throws NotFoundHttpException
     * @throws RollCantBeSentToGlowException
     */
    public function __invoke(GlowCheckInCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $this->sendRollToGlowCheckInService->handle($command->rollId);
    }
}
