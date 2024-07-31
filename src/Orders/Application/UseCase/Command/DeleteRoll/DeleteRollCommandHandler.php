<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\DeleteRoll;

use App\Orders\Application\AccessControll\AccessControlService;
use App\Orders\Infrastructure\Repository\RollRepository;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DeleteRollCommandHandler.
 */
readonly class DeleteRollCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param RollRepository       $rollRepository       the RollRepository instance
     * @param AccessControlService $accessControlService the AccessControlService instance
     */
    public function __construct(
        private RollRepository $rollRepository,
        private AccessControlService $accessControlService
    ) {
    }

    /**
     * Handle the delete roll command.
     *
     * @param DeleteRollCommand $deleteRollCommand the delete roll command object
     *
     * @throws NotFoundHttpException if roll not found
     */
    public function __invoke(DeleteRollCommand $deleteRollCommand): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to add lamination.');
        $roll = $this->rollRepository->findById($deleteRollCommand->id);

        if (is_null($roll)) {
            throw new NotFoundHttpException('Roll not found');
        }

        $this->rollRepository->remove($roll);
    }
}
