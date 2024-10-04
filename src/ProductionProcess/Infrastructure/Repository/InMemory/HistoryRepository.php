<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Repository\InMemory;

use App\ProductionProcess\Domain\Aggregate\Roll\History\History;
use App\ProductionProcess\Domain\Repository\HistoryRepositoryInterface;

/**
 * Class HistoryRepository.
 */
class HistoryRepository implements HistoryRepositoryInterface
{
    private array $histories = [];

    /**
     * Saves a History entity.
     *
     * @param History $history The History entity to be saved
     */
    public function add(History $history): void
    {
        $this->histories[] = $history;
    }

    /**
     * Finds a History entity by a rollId.
     *
     * @param int $rollId the rollId to search for
     *
     * @return History[] the found History entity or null if no History entity was found
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByRollId(int $rollId): array
    {
        return array_filter($this->histories, function (History $history) use ($rollId) {
            return $history->rollId === $rollId;
        });
    }
}
