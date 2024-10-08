<?php

namespace App\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Repository\RollFilter;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
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
        $roll->removePrintedProducts();
        $this->getEntityManager()->remove($roll);
        $this->getEntityManager()->flush();
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
            $qb->where('r.filmId IN (:filmIds)');
            $qb->setParameter('filmIds', $rollFilter->filmIds);
        }

        if ($rollFilter->filmType) {
            $qb->join('r.printer', 'p')
                ->andWhere('JSONB_CONTAINS(p.filmTypes, :filmType) = true')
                ->setParameter('filmType', json_encode($rollFilter->filmType))
            ;
        }

        if ($rollFilter->process) {
            $qb->andWhere('r.process = :process');
            $qb->setParameter('process', $rollFilter->process->value);
        }

        $query = $qb->getQuery();

        return $query->getResult();
    }

    /**
     * Finds a roll by its film ID.
     *
     * If a film ID is provided, it will return the first roll that matches the film ID.
     * If the film ID is not provided or the roll is not found, it will return null.
     *
     * @param int|null $filmId the ID of the film to search for
     *
     * @return Roll|null the found roll, or null if no roll was found
     */
    public function findByFilmId(?int $filmId = null): ?Roll
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
