<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Domain\Aggregate\Error;
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
}
