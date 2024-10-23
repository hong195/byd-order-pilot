<?php

/**
 * Repository implementation for Roll History Statistics using Doctrine ORM.
 */

namespace App\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Application\UseCase\Query\RollHistoryStatistics\RollHistoryStatisticsFilterCriteria;
use App\ProductionProcess\Domain\Aggregate\Roll\History\History;
use App\ProductionProcess\Domain\Aggregate\Roll\History\Type;
use App\ProductionProcess\Domain\Repository\RollHistoryStatisticsRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Handles the retrieval of Roll History Statistics based on specified criteria.
 */
class RollHistoryStatisticsRepository extends ServiceEntityRepository implements RollHistoryStatisticsRepositoryInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }

    /**
     * @param RollHistoryStatisticsFilterCriteria $criteria
     *
     * @return History[]
     */
    public function findByCriteria(RollHistoryStatisticsFilterCriteria $criteria): array
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
