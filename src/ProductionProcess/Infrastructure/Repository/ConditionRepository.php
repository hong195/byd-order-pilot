<?php

declare(strict_types=1);

/**
 * Class ErrorRepository.
 */

namespace App\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Domain\Aggregate\Printer\Condition;
use App\ProductionProcess\Domain\Repository\Printer\ConditionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class HistoryRepository.
 */
class ConditionRepository extends ServiceEntityRepository implements ConditionRepositoryInterface
{
    /**
     * Constructs a new OrderRepository instance.
     *
     * @param ManagerRegistry $registry the manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Condition::class);
    }

    /**
     * Add Condition entity to the database.
     *
     * @param Condition $condition The Error entity to be added
     */
    public function add(Condition $condition): void
    {
        $this->getEntityManager()->persist($condition);
        $this->getEntityManager()->flush();
    }

    /**
     * Retrieves all records from the database and returns them as a Collection.
     *
     * @return Collection<Condition> Collection of all records
     */
    public function all(): Collection
    {
        return new ArrayCollection($this->findAll());
    }
}
