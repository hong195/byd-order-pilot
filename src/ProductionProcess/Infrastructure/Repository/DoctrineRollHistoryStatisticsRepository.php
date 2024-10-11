<?php

/**
 * Repository implementation for Roll History Statistics using Doctrine ORM.
 */

namespace App\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Application\UseCase\Query\Repository\RollHistoryStatisticsRepositoryInterface;
use App\ProductionProcess\Application\UseCase\Query\RollHistoryStatistics\RollHistoryStatisticsFilterCriteria;
use App\ProductionProcess\Domain\Aggregate\Roll\History\History;
use App\ProductionProcess\Domain\Aggregate\Roll\History\Type;
use App\ProductionProcess\Domain\DTO\RollHistoryStatistics;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Repository implementation for Roll History Statistics using Doctrine ORM.
 */
readonly class DoctrineRollHistoryStatisticsRepository implements RollHistoryStatisticsRepositoryInterface
{
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @param RollHistoryStatisticsFilterCriteria $criteria
     *
     * @return RollHistoryStatistics[]
     */
    public function findByCriteria(RollHistoryStatisticsFilterCriteria $criteria): array
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('h')
            ->from(History::class, 'h')
            ->where('h.type = :type')->setParameter('type', Type::PROCESS_CHANGED->value);

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

        $results = $qb->getQuery()->getArrayResult();

        return array_map(function ($result) {
            return new RollHistoryStatistics(
                $result['rollId'],
                $result['process'],
                $result['type']->value,
                $result['happenedAt']
            );
        }, $results);
    }
}
