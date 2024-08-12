<?php

namespace App\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Roll;
use App\Orders\Domain\Repository\RollFilter;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Orders\Domain\ValueObject\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * Finds rolls by roll type.
     *
     * @return Roll[] an array of rolls matching the roll type
     */
    public function findByStatus(Status $status): array
    {
        $qb = $this->createQueryBuilder('r');

        $qb->where('r.status = :status');
        $qb->setParameter('status', $status->value);

        $query = $qb->getQuery();

        $query->setMaxResults(10);

        return $query->getResult();
    }

    /**
     * Finds rolls based on the given filter.
     *
     * @param RollFilter $rollFilter the filter for rolls
     *
     * @return Roll[] the array of rolls found
     */
    public function findByFilter(RollFilter $rollFilter): array
    {
        $qb = $this->createQueryBuilder('r');

        if (!empty($rollFilter->filmIds)) {
            $qb->andWhere('r.filmId IN (:filmIds)');
            $qb->setParameter('filmId', $rollFilter->filmIds);
        }

        if ($rollFilter->status) {
            $qb->andWhere('r.status = :status');
            $qb->setParameter('status', $rollFilter->status->value);
        }

        $query = $qb->getQuery();

        $query->setMaxResults(10);

        return $query->getResult();
    }

    /**
     * Finds a roll by its film ID.
     *
     * @param int $filmId the ID of the film
     *
     * @return Roll|null the found roll, or null if no roll was found
     */
    public function findByFilmId(int $filmId): ?Roll
    {
        return $this->findBy(['filmId' => $filmId])[0] ?? null;
    }

    /**
     * Saves multiple rolls.
     *
     * @param iterable<Roll> $rolls the rolls to save
     */
    public function saveRolls(iterable $rolls): void
    {
        foreach ($rolls as $roll) {
            $this->getEntityManager()->persist($roll);
        }

        $this->getEntityManager()->flush();
    }
}
