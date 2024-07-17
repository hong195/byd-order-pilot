<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Command\AddRoll;

use App\Rolls\Domain\Factory\RollFactory;
use App\Rolls\Infrastructure\Repository\RollRepository;
use App\Shared\Application\Command\CommandHandlerInterface;

readonly class AddRollCommandHandler implements CommandHandlerInterface
{
    public function __construct(private RollRepository $rollRepository)
    {
    }

    public function __invoke(AddRollCommand $addRollCommand): int
    {
        $roll = (new RollFactory())->create(
            name: $addRollCommand->name,
            quality: $addRollCommand->quality,
            rollType: $addRollCommand->rollType,
        );

        $this->rollRepository->add($roll);

        return $roll->getId();
    }
}
