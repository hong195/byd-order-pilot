<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UpdateLamination;

use App\Orders\Application\AccessControll\AccessControlService;
use App\Orders\Domain\Service\LaminationService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

readonly class UpdateLaminationCommandHandler implements CommandHandlerInterface
{
    public function __construct(private LaminationService $laminationService,
        private AccessControlService $accessControlService
    ) {
    }

    /**
     * Update a lamination.
     *
     * This method gets a lamination using the provided ID from the command. Then it updates the lamination's properties
     * using the provided values from the command.
     *
     * @param UpdateLaminationCommand $updateLaminationCommand the command containing the update data
     */
    public function __invoke(UpdateLaminationCommand $updateLaminationCommand): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to handle resource.');
        $this->laminationService->getLamination($updateLaminationCommand->id);

        $this->laminationService->updateLamination(
            id: $updateLaminationCommand->id,
            name: $updateLaminationCommand->name,
            quality: $updateLaminationCommand->quality,
            laminationType: $updateLaminationCommand->laminationType,
            length: $updateLaminationCommand->length,
            priority: $updateLaminationCommand->priority,
            qualityNotes: $updateLaminationCommand->qualityNotes
        );
    }
}
