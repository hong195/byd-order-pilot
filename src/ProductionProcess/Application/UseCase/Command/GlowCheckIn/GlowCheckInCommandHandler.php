<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\GlowCheckIn;

use App\ProductionProcess\Domain\Exceptions\RollCantBeSentToGlowException;
use App\ProductionProcess\Domain\Service\Roll\GlowCheckInService;
use App\Shared\Application\Command\CommandHandlerInterface;
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
     * @param GlowCheckInService $sendRollToGlowCheckInService the send roll to Glow CheckIn service
     */
    public function __construct(private GlowCheckInService $sendRollToGlowCheckInService)
    {
    }

    /**
     * @throws NotFoundHttpException
     * @throws RollCantBeSentToGlowException
     */
    public function __invoke(GlowCheckInCommand $command): void
    {
        $this->sendRollToGlowCheckInService->handle($command->rollId);
    }
}
