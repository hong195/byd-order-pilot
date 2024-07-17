<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Command\AddLamination;

use App\Rolls\Application\AccessControll\AccessControlService;
use App\Rolls\Domain\Factory\LaminationFactory;
use App\Rolls\Infrastructure\Repository\LaminationRepository;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * This class handles the AddLaminationCommand.
 */
readonly class AddLaminationCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param LaminationRepository $laminationRepository the lamination repository instance
     */
    public function __construct(
        private LaminationRepository $laminationRepository,
        private AccessControlService $accessControlService
    ) {
    }

    /**
     * Invokes the AddLaminationCommand.
     *
     * @param AddLaminationCommand $laminationCommand the AddLaminationCommand instance
     *
     * @return int the ID of the newly added lamination
     */
    public function __invoke(AddLaminationCommand $laminationCommand): int
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to add lamination.');

        $lamination = (new LaminationFactory())->create(
            name: $laminationCommand->name,
            quality: $laminationCommand->quality,
            laminationType: $laminationCommand->laminationType,
        );

        $this->laminationRepository->add($lamination);

        return $lamination->getId();
    }
}
