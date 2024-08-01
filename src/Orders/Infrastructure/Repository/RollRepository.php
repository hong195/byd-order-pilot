<?php

namespace App\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Roll\Roll;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Shared\Domain\Repository\PaginationResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository class for Roll entities.
 * Extends the ServiceEntityRepository class and implements the RollRepositoryInterface.
 */
class RollRepository extends ServiceEntityRepository implements RollRepositoryInterface
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry the manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Roll::class);
    }

    /**
     * Add a roll to the database.
     *
     * @param Roll $roll the roll to add
     */
    public function add(Roll $roll): void
    {
        $this->getEntityManager()->persist($roll);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds a roll by its ID.
     *
     * @param int $id the ID of the roll to find
     *
     * @return Roll|null the found roll, or null if no roll was found
     */
    public function findById(int $id): ?Roll
    {
        return $this->find($id);
    }

    /**
     * Saves a Roll entity.
     *
     * @param Roll $roll The Roll entity to save
     */
    public function save(Roll $roll): void
    {
        $this->getEntityManager()->persist($roll);
        $this->getEntityManager()->flush();
    }

    /**
     * Removes a Roll entity.
     *
     * @param Roll $roll The Roll entity to remove
     */
    public function remove(Roll $roll): void
    {
        $this->getEntityManager()->remove($roll);
        $this->getEntityManager()->flush();
    }

    /**
     * Find paged items.
     *
     * @param int $page The page number, default is 1
     *
     * @return PaginationResult The pagination result object
     */
    public function findPagedItems(int $page = 1): PaginationResult
    {
        $qb = $this->createQueryBuilder('r');

        $qb->orderBy('r.priority', 'ASC');
        $qb->addOrderBy('r.length', 'ASC');

        $query = $qb->getQuery();

        $query->setMaxResults(10);

        $paginator = new Paginator($query);

        return new PaginationResult($query->getResult(), $paginator->count());
    }
}
