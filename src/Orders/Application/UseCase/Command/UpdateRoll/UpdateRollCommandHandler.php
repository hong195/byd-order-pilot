<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UpdateRoll;

use App\Orders\Application\AccessControll\AccessControlService;
use App\Orders\Domain\Aggregate\Roll\RollType;
use App\Orders\Domain\Aggregate\ValueObject\Quality;
use App\Orders\Infrastructure\Repository\RollRepository;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class UpdateRollCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param RollRepository       $rollRepository       the roll repository instance
     * @param AccessControlService $accessControlService the access control service instance
     */
    public function __construct(private RollRepository $rollRepository, private AccessControlService $accessControlService)
    {
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
