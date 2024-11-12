<?php

namespace App\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Repository\RollFilter;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Process;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * Finds a roll by its film ID.
     *
     * @param int $filmId the film ID of the roll to find
     *
     * @return Collection<Roll> the found roll, or null if no roll was found
     */
    public function findByFilmId(int $filmId): Collection
    {
        return new ArrayCollection($this->findBy(['filmId' => $filmId]));
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
     * @return ArrayCollection<Roll> the array of rolls found
     */
    public function findByFilter(RollFilter $rollFilter): ArrayCollection
    {
        $qb = $this->createQueryBuilder('r');

        if (!empty($rollFilter->filmIds)) {
            $qb->andWhere('r.filmId IN (:filmIds)');
            $qb->setParameter('filmIds', $rollFilter->filmIds);
        }

        if ($rollFilter->process) {
            $qb->andWhere('r.process = :process');
            $qb->setParameter('process', $rollFilter->process->value);
        }

        $query = $qb->getQuery();

        return new ArrayCollection($query->getResult());
    }

    /**
     * Finds product check-ins.
     *
     * @return Collection<Roll> the collection of product check-ins
     */
    public function findForAutoArrange(): Collection
    {
        $qb = $this->createQueryBuilder('r');

        $qb->andWhere('r.process = :process');
        $qb->setParameter('process', Process::ORDER_CHECK_IN);

        $qb->andWhere('r.isLocked = :isLocked')
            ->setParameter('isLocked', false);

        $query = $qb->getQuery();

        return new ArrayCollection($query->getResult());
    }
}
