<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Command\UpdateRoll;

use App\Rolls\Application\AccessControll\AccessControlService;
use App\Rolls\Domain\Aggregate\Quality;
use App\Rolls\Domain\Aggregate\Roll\RollType;
use App\Rolls\Infrastructure\Repository\RollRepository;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class UpdateRollCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RollRepository $rollRepository,
        private AccessControlService $accessControlService
    ) {
    }

    /**
     * Update a Roll entity.
     *
     * @param UpdateRollCommand $updateRollCommand The command to update the Roll entity
     *
     * @throws NotFoundHttpException if the Roll entity is not found
     */
    public function __invoke(UpdateRollCommand $updateRollCommand): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to add lamination.');
        $roll = $this->rollRepository->findById($updateRollCommand->id);

        if (is_null($roll)) {
            throw new NotFoundHttpException('Roll not found');
        }

        $roll->changeName($updateRollCommand->name);
        $roll->changeRollType(RollType::from($updateRollCommand->rollType));
        $roll->changeQuality(Quality::from($updateRollCommand->quality));

        if ($updateRollCommand->length !== $roll->getLength()) {
            $roll->updateLength($updateRollCommand->length);
        }

        $this->rollRepository->save($roll);
    }
}
