<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\UnAssignEmployeeFromRoll;

use App\ProductionProcess\Domain\Aggregate\Roll\History\Type;
use  App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\History\HistorySyncService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class UnAssignEmployeeFromRollCommandHandler implements CommandHandlerInterface
{
    /**
     * Class MyClass.
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private HistorySyncService $historyListService)
    {
    }

    /**
     * Invokes the command to change the order priority.
     *
     * @param UnAssignEmployeeFromRollCommand $command the change order priority command instance
     *
     * @throws NotFoundHttpException if the roll is not found
     */
    public function __invoke(UnAssignEmployeeFromRollCommand $command): void
    {
        $roll = $this->rollRepository->findById($command->rollId);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        $roll->setEmployeeId(null);

        $this->rollRepository->save($roll);

        $this->historyListService->record(rollId: $roll->getId(), process: $roll->getProcess(), type: Type::EMPLOYEE_UNASSIGNED);
    }
}
