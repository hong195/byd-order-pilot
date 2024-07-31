<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\AddRoll;

use App\Orders\Application\AccessControll\AccessControlService;
use App\Orders\Domain\Factory\RollFactory;
use App\Orders\Infrastructure\Repository\RollRepository;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

readonly class AddRollCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RollRepository $rollRepository,
        private AccessControlService $accessControlService
    ) {
    }

    /**
     * Invokes the AddRollCommand handler.
     *
     * @param AddRollCommand $addRollCommand the command object containing the roll details
     *
     * @return int the ID of the added roll
     */
    public function __invoke(AddRollCommand $addRollCommand): int
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to handle resource.');
        $roll = (new RollFactory())->create(
            name: $addRollCommand->name,
            quality: $addRollCommand->quality,
            rollType: $addRollCommand->rollType,
        );

        $this->rollRepository->add($roll);

        return $roll->getId();
    }
}
