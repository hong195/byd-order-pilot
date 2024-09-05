<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\ReprintRoll;

use App\Orders\Domain\Exceptions\OrderReprintException;
use App\Orders\Domain\Service\Roll\ReprintRoll;
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
     * Class constructor.
     *
     * @param AccessControlService $accessControlService the access control service
     */
    public function __construct(private ReprintRoll $reprintRoll, private AccessControlService $accessControlService)
    {
    }

    /**
     * Handles a PackMainProductCommand.
     *
     * @param ReprintRollCommand $command The command to handle
     *
     * @throws NotFoundHttpException
     * @throws OrderReprintException
     */
    public function __invoke(ReprintRollCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');
        $this->reprintRoll->handle($command->rollId);
    }
}
