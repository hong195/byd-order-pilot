<?php

declare(strict_types=1);

/**
 * Class ErrorRepository.
 */

namespace App\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Application\DTO\Error\EmployerErrorData;
use App\ProductionProcess\Domain\Aggregate\Error;
use App\ProductionProcess\Domain\Repository\DateRangeFilter;
use App\ProductionProcess\Domain\Repository\ErrorFilter;
use App\ProductionProcess\Domain\Repository\ErrorRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Process;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class HistoryRepository.
 */
class ErrorRepository extends ServiceEntityRepository implements ErrorRepositoryInterface
{
    /**
     * Constructs a new OrderRepository instance.
     *
     * @param ManagerRegistry $registry the manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Error::class);
    }

    /**
     * Add an Error entity to the database.
     *
     * @param Error $error The Error entity to be added
     */
    public function add(Error $error): void
    {
        $this->getEntityManager()->persist($error);
        $this->getEntityManager()->flush();
    }

    /**
     * Find entities by responsible employee ID.
     *
     * @param int $responsibleEmployeeId The ID of the responsible employee
     *
     * @return Error[] The array of entities found based on responsible employee ID
     */
    public function findByResponsibleEmployeeId(int $responsibleEmployeeId): array
    {
        $query = $this->createQueryBuilder('e');

        $query->where('e.responsibleEmployeeId = :responsibleEmployeeId')
            ->setParameter('responsibleEmployeeId', $responsibleEmployeeId)
            ->orderBy('e.createdAt', 'ASC');

        return $query->getQuery()->getResult();
    }

    /**
     * Finds items by process.
     *
     * @param Process $process The process entity
     *
     * @return Error[] An array of items related to the specified process
     */
    public function findByProcess(Process $process): array
    {
        $query = $this->createQueryBuilder('e');

        $query->where('e.process = :process')
            ->setParameter('process', $process)
            ->orderBy('e.createdAt', 'ASC');

        return $query->getQuery()->getResult();
    }

    /**
     * Find entities by the provided error filter.
     *
     * @param ErrorFilter $filter The filter object to apply when searching for entities
     *
     * @return Error[] An array of entities that match the provided error filter
     */
    public function findByFilter(ErrorFilter $filter): array
    {
        $qb = $this->createQueryBuilder('e');

        if ($filter->process) {
            $qb->andWhere('e.process = :process')
                ->setParameter('process', $filter->process);
        }

        if ($filter->noticerId) {
            $qb->andWhere('e.noticerId = :noticerId')
                ->setParameter('noticerId', $filter->noticerId);
        }

        if ($filter->responsibleEmployeeId) {
            $qb->andWhere('e.responsibleEmployeeId = :responsibleEmployeeId')
                ->setParameter('responsibleEmployeeId', $filter->responsibleEmployeeId);
        }

        if ($filter->from) {
            $qb->andWhere('e.createdAt >= :from')
                ->setParameter('from', $filter->from);
        }

        if ($filter->to) {
            $qb->andWhere('e.createdAt <= :to')
                ->setParameter('to', $filter->to);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Find entities by the provided error filter.
     *
     * @param DateRangeFilter $filter The filter object to apply when searching for entities
     *
     * @return EmployerErrorData[] An array of entities that match the provided error filter
     */
    public function findEmployerErrorsByFilter(DateRangeFilter $filter): array
    {
        $qb = $this->createQueryBuilder('e')
            ->select(
                'e.responsibleEmployeeId',
                'COUNT(e) AS total',
                'SUM(CASE WHEN e.process = :orderCheckIn THEN 1 ELSE 0 END) AS order_check_in',
                'SUM(CASE WHEN e.process = :printingCheckIn THEN 1 ELSE 0 END) AS printing_check_in',
                'SUM(CASE WHEN e.process = :glowCheckIn THEN 1 ELSE 0 END) AS glow_check_in',
                'SUM(CASE WHEN e.process = :cuttingCheckIn THEN 1 ELSE 0 END) AS cutting_check_in'
            )
            ->groupBy('e.responsibleEmployeeId');

        // Setting the process type parameters using enum values
        $qb->setParameter('orderCheckIn', Process::ORDER_CHECK_IN->value)
            ->setParameter('printingCheckIn', Process::PRINTING_CHECK_IN->value)
            ->setParameter('glowCheckIn', Process::GLOW_CHECK_IN->value)
            ->setParameter('cuttingCheckIn', Process::CUTTING_CHECK_IN->value);


        if ($filter->from) {
            $qb->andWhere('e.createdAt >= :from')
                ->setParameter('from', $filter->from);
        }

        if ($filter->to) {
            $qb->andWhere('e.createdAt <= :to')
                ->setParameter('to', $filter->to);
        }

        return $qb->getQuery()->getResult();
    }
}
