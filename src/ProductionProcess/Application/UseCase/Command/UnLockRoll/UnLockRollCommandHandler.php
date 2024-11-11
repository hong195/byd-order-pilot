<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\UnLockRoll;

use App\ProductionProcess\Domain\Exceptions\LockingRollException;
use App\ProductionProcess\Domain\Service\Roll\UnLockRollService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
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
     * @param AccessControlService $accessControlService the access control service instance
     * @param UnLockRollService    $unLockRollService    the unlock roll service instance
     */
    public function __construct(private AccessControlService $accessControlService, private UnLockRollService $unLockRollService)
    {
    }

    /**
     * @throws NotFoundHttpException
     * @throws LockingRollException
     */
    public function __invoke(UnLockRollCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $this->unLockRollService->unlock($command->rollId);
    }
}
