<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Domain\Aggregate\Roll\History\History;
use App\ProductionProcess\Domain\Repository\HistoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class HistoryRepository.
 */
class HistoryRepository extends ServiceEntityRepository implements HistoryRepositoryInterface
{
    private array $history = [];

    /**
     * Constructs a new OrderRepository instance.
     *
     * @param ManagerRegistry $registry the manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }

    /**
     * Saves a History entity.
     *
     * @param History $history The History entity to be saved
     */
    public function add(History $history): void
    {
        $this->getEntityManager()->persist($history);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds the full history for a given roll ID recursively.
     *
     * @param int $rollId the roll ID to search for
     *
     * @return array the full history matching the roll ID
     */
    public function findFullHistory(int $rollId): array
    {
        $history = [];

        $fetchHistory = function (int $rollId) use (&$fetchHistory, &$history) {
            $currentHistory = $this->findBy(['rollId' => $rollId]);

            $history = array_merge($history, $currentHistory);

            foreach ($currentHistory as $record) {
                if ($record->getParentRollId()) {
                    $fetchHistory($record->getParentRollId());
                }
            }
        };

        $fetchHistory($rollId);

        usort($history, fn ($a, $b) => $a->happenedAt <=> $b->happenedAt);

        return $history;
    }
}
