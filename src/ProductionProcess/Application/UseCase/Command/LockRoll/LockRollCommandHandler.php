<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\LockRoll;

use App\ProductionProcess\Domain\Exceptions\LockingRollException;
use App\ProductionProcess\Domain\Service\Roll\LockRollService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class GlowCheckInCommandHandler.
 *
 * This class is responsible for handling the SendGlowToPrintCheckIntCommand.
 */
readonly class LockRollCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService $accessControlService the AccessControlService instance
     * @param LockRollService      $lockRollService      the LockRollService instance
     */
    public function __construct(private AccessControlService $accessControlService, private LockRollService $lockRollService)
    {
    }

    /**
     * @throws NotFoundHttpException
     * @throws LockingRollException
     */
    public function __invoke(LockRollCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $this->lockRollService->lock($command->rollId);
    }
}
