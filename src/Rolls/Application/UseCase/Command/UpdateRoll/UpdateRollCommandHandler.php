<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Command\UpdateRoll;

use App\Rolls\Domain\Aggregate\Roll\Quality;
use App\Rolls\Domain\Aggregate\Roll\RollType;
use App\Rolls\Infrastructure\Repository\RollRepository;
use App\Shared\Application\Command\CommandHandlerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class UpdateRollCommandHandler implements CommandHandlerInterface
{
    public function __construct(private RollRepository $rollRepository)
    {
    }

    public function __invoke(UpdateRollCommand $updateRollCommand): void
    {
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
