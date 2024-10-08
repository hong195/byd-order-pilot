<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\ReprintRoll;

use App\ProductionProcess\Domain\Exceptions\RollErrorManagementException;
use App\ProductionProcess\Domain\Service\Roll\ReprintRollService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class ReprintRollCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructor.
     */
    public function __construct(private ReprintRollService $reprintRollService, private AccessControlService $accessControlService)
    {
    }

    /**
     * Handles a PackMainProductCommand.
     *
     * @param ReprintRollCommand $command The command to handle
     *
     * @throws NotFoundHttpException
     * @throws RollErrorManagementException
     */
    public function __invoke(ReprintRollCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $this->reprintRollService->reprint(
            rollId: $command->rollId,
            process: $command->process,
            reason: $command->reason
        );
    }
}
