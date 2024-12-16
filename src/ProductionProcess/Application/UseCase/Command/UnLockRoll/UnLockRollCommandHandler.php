<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\UnLockRoll;

use App\ProductionProcess\Domain\Exceptions\LockingRollException;
use App\ProductionProcess\Domain\Service\Roll\UnLockRollService;
use App\Shared\Application\Command\CommandHandlerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class GlowCheckInCommandHandler.
 *
 * This class is responsible for handling the SendGlowToPrintCheckIntCommand.
 */
readonly class UnLockRollCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param UnLockRollService $unLockRollService the unlock roll service instance
     */
    public function __construct(private UnLockRollService $unLockRollService)
    {
    }

    /**
     * @throws NotFoundHttpException
     * @throws LockingRollException
     */
    public function __invoke(UnLockRollCommand $command): void
    {
        $this->unLockRollService->unlock($command->rollId);
    }
}
