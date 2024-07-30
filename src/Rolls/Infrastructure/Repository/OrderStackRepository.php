<?php

declare(strict_types=1);

namespace App\Rolls\Infrastructure\Repository;

use App\Rolls\Domain\Aggregate\OrderStack\OrderStack;
use App\Rolls\Domain\Repository\OrderStackFilter;
use App\Rolls\Domain\Repository\OrderStackRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Constructs a new instance of the class.
 *
 * @param ManagerRegistry $registry the manager registry
 */
class OrderStackRepository extends ServiceEntityRepository implements OrderStackRepositoryInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param ManagerRegistry $registry the manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderStack::class);
    }

    /**
     * Saves an OrderStack to the database.
     *
     * @param OrderStack $orderStack The OrderStack to save
     */
    public function save(OrderStack $orderStack): void
    {
        $this->getEntityManager()->persist($orderStack);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds an OrderStack by its ID.
     *
     * @param string $id The ID of the OrderStack to find
     *
     * @return OrderStack|null The found OrderStack, or null if not found
     */
    public function findById(string $id): ?OrderStack
    {
        return $this->find($id);
    }

    /**
     * Finds all OrderStacks.
     *
     * @return OrderStack[] The array of OrderStacks
     */
    public function findQueried(OrderStackFilter $orderStackFilter): array
    {
        $qb = $this->createQueryBuilder('or');

        if ($orderStackFilter->rollType) {
            $qb->where($qb->expr()->like('or.rollType', ':rollType'))
                ->setParameter('rollType', $orderStackFilter->rollType);
        }

        if ($orderStackFilter->laminationType) {
            $qb->where($qb->expr()->like('or.laminationType', ':laminationType'))
                ->setParameter('laminationType', $orderStackFilter->laminationType);
        }

        $query = $qb->getQuery();

        $query->setMaxResults(100);

        return $query->getResult();
    }
}
