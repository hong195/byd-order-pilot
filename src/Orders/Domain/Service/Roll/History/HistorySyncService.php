<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Roll\History;

use App\Orders\Domain\Factory\HistoryFactory;
use App\Orders\Domain\Repository\HistoryRepositoryInterface;
use App\Orders\Domain\Repository\RollRepositoryInterface;

final readonly class HistorySyncService
{
	public function __construct(private RollRepositoryInterface $rollRepository, private HistoryRepositoryInterface $historyRepository, private HistoryFactory $historyFactory)
	{
	}

	private function start(int $rollId): void
	{
		$roll = $this->rollRepository->findById($rollId);

		$history = $this->historyFactory->fromRoll($roll);

		$this->historyRepository->save($history);
	}

	public function sync(int $rollId): void
	{
		$history = $this->historyRepository->findUnfinished($rollId);

		if (!$history) {
			$this->start($rollId);
			return;
		}

		$history->finish();

		$this->historyRepository->save($history);
	}

	public function copyHistory(int $parentRollId, array $childrenRollId): void
	{
		$histories = $this->historyRepository->findByRollId($parentRollId);

		$newHistories = [];
		foreach ($histories as $history) {
			foreach ($childrenRollId as $copyRollId) {
				$newHistories[] = $this->historyFactory->make($copyRollId, $history->process, $history->startedAt);
			}
		}

		$this->historyRepository->saveMany($newHistories);

		$this->historyRepository->deleteAll($histories);
	}
}
