<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\History;

use App\ProductionProcess\Domain\Aggregate\Roll\History\Type;
use App\ProductionProcess\Domain\Factory\HistoryFactory;
use App\ProductionProcess\Domain\Repository\HistoryRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Process;

/**
 * Class HistorySyncService.
 *
 * This class is responsible for synchronizing and copying history records for rolls.
 */
final readonly class HistorySyncService
{
    /**
     * Constructs a new instance of the class.
     *
     * @param RollRepositoryInterface    $rollRepository    the roll repository
     * @param HistoryRepositoryInterface $historyRepository the history repository
     * @param HistoryFactory             $historyFactory    the history factory
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private HistoryRepositoryInterface $historyRepository, private HistoryFactory $historyFactory)
    {
    }

    /**
     * Syncs the history with the specified roll ID.
     *
     * If there is no unfinished history found for the given roll ID, a new history will be started.
     * Otherwise, the existing history will be finished and saved.
     *
     * @param int $rollId the ID of the roll to sync the history for
     */
    public function record(int $rollId, Process $process, Type $type): void
    {
        $roll = $this->rollRepository->findById($rollId);

        $history = $this->historyFactory->make(
			rollId: $rollId,
			process: $process,
			happenedAt: new \DateTimeImmutable(),
			type: $type,
			parentRollId: $roll->getParentRoll()?->getId(),
			employeeId: $roll->getEmployeeId()
		);

        $this->historyRepository->add($history);
    }
}
