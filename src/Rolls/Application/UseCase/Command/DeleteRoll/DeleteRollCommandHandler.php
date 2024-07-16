<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Command\DeleteRoll;

use App\Rolls\Infrastructure\Repository\RollRepository;
use App\Shared\Application\Command\CommandHandlerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class DeleteRollCommandHandler implements CommandHandlerInterface
{
    public function __construct(private RollRepository $rollRepository)
    {
    }

    public function __invoke(DeleteRollCommand $deleteRollCommand): void
    {
        $roll = $this->rollRepository->findById($deleteRollCommand->id);

        if (is_null($roll)) {
            throw new NotFoundHttpException('Roll not found');
        }

        $this->rollRepository->remove($roll);
    }
}
