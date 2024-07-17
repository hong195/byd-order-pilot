<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Command\DeleteLamination;

use App\Rolls\Application\AccessControll\AccessControlService;
use App\Rolls\Domain\Service\LaminationService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

readonly class DeleteLaminationCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private LaminationService $laminationService,
        private AccessControlService $accessControlService
    ) {
    }

    /**
     * Handles the delete lamination command.
     *
     * @param DeleteLaminationCommand $deleteLaminationCommand the delete lamination command object
     *
     * */
    public function __invoke(DeleteLaminationCommand $deleteLaminationCommand): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to handle resource.');
        $lamination = $this->laminationService->getLamination($deleteLaminationCommand->id);
        $this->laminationService->deleteLamination($lamination->getId());
    }
}
