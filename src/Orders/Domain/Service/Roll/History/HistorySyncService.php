<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Roll\History;

use App\Orders\Domain\Aggregate\Roll\History\Type;
use App\Orders\Domain\Factory\HistoryFactory;
use App\Orders\Domain\Repository\HistoryRepositoryInterface;
use App\Orders\Domain\Repository\RollRepositoryInterface;

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
    public function record(int $rollId, Type $type): void
    {
        $roll = $this->rollRepository->findById($rollId);

        $history = $this->historyFactory->make(rollId: $rollId, process: $roll->getProcess(), happenedAt: new \DateTimeImmutable(), type: $type, employeeId: $roll->getEmployeeId());

        $this->historyRepository->add($history);
    }

    /**
     * Copies the history of a parent roll to multiple children rolls.
     *
     * @param int   $parentRollId   the ID of the parent roll
     * @param int[] $childrenRollId an array of child roll IDs
     */
    public function copyHistory(int $parentRollId, array $childrenRollId): void
    {
        $histories = $this->historyRepository->findByRollId($parentRollId);

        foreach ($histories as $history) {
            foreach ($childrenRollId as $copyRollId) {
                $copiedHistory = $this->historyFactory->make(
					rollId: $copyRollId,
					process: $history->process,
					happenedAt: $history->happenedAt,
					type: $history->type,
					employeeId: $history->getEmployeeId()
				);
                $this->historyRepository->add($copiedHistory);

                $this->historyRepository->delete($history);
            }
        }
    }
}
