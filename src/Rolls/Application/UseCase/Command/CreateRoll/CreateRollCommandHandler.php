<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Command\CreateRoll;

use App\Rolls\Domain\Factory\RollFactory;
use App\Rolls\Infrastructure\Repository\RollRepository;
use App\Shared\Application\Command\CommandHandlerInterface;

readonly class CreateRollCommandHandler implements CommandHandlerInterface
{
    public function __construct(private RollRepository $rollRepository)
    {
    }

    public function __invoke(CreateRollCommand $createRollCommand): int
    {
        $roll = (new RollFactory())->create(
            name: $createRollCommand->name,
            quality: $createRollCommand->quality,
            rollType: $createRollCommand->rollType,
        );

        $this->rollRepository->add($roll);

        return $roll->getId();
    }
}
