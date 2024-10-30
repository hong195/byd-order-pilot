<?php

declare(strict_types=1);

/**
 * Class HistoryRepository.
 */

namespace App\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Domain\Aggregate\Roll\History\History;
use App\ProductionProcess\Domain\Aggregate\Roll\History\Type;
use App\ProductionProcess\Domain\Repository\HistoryRepositoryInterface;
use App\ProductionProcess\Application\UseCase\Query\FetchRollHistoryStatistics\RollHistoryStatisticsFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class HistoryRepository.
 */
class HistoryRepository extends ServiceEntityRepository implements HistoryRepositoryInterface
{
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
        $query = $this->createQueryBuilder('h');

        $query->where('h.rollId = :rollId')
            ->setParameter('rollId', $rollId)
            ->orderBy('h.happenedAt', 'ASC');

        return $query->getQuery()->getResult();
    }

    /**
     * @param RollHistoryStatisticsFilter $criteria
     *
     * @return History[]
     */
    public function findByCriteria(RollHistoryStatisticsFilter $criteria): array
    {
        $qb = $this->createQueryBuilder('h')
            ->select('h')
            ->where('h.type = :type')
            ->setParameter('type', Type::PROCESS_CHANGED->value);

        if ($criteria->getEmployeeId()) {
            $qb->andWhere('h.employeeId = :employeeId')
                ->setParameter('employeeId', $criteria->getEmployeeId());
        }

        if ($criteria->getProcess()) {
            $qb->andWhere('h.process = :process')
                ->setParameter('process', $criteria->getProcess());
        }

        if ($criteria->getFrom()) {
            $qb->andWhere('h.happenedAt >= :from')
                ->setParameter('from', $criteria->getFrom());
        }

        if ($criteria->getTo()) {
            $qb->andWhere('h.happenedAt <= :to')
                ->setParameter('to', $criteria->getTo());
        }

        return $qb->getQuery()->getResult();
    }
}
