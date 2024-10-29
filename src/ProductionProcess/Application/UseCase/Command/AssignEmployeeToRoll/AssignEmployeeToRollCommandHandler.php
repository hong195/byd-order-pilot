<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\AssignEmployeeToRoll;

use App\ProductionProcess\Domain\Aggregate\Roll\History\Type;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\History\HistorySyncService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class AssignEmployeeToRollCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService    $accessControlService the access control service
     * @param RollRepositoryInterface $rollRepository       the roll repository
     */
    public function __construct(private AccessControlService $accessControlService, private RollRepositoryInterface $rollRepository, private HistorySyncService $historySyncService)
    {
    }

    /**
     * Invokes the AssignEmployeeToRollCommand.
     *
     * @param AssignEmployeeToRollCommand $command the assign employee to roll command
     *
     * @throws NotFoundHttpException when roll is not found
     */
    public function __invoke(AssignEmployeeToRollCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $roll = $this->rollRepository->findById($command->rollId);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        $roll->setEmployeeId($command->employeeId);

        $this->rollRepository->save($roll);

        // Add a new history record for the roll
        $this->historySyncService->record(rollId: $roll->getId(), process: $roll->getProcess(), type: Type::EMPLOYEE_ASSIGNED);
    }
}
