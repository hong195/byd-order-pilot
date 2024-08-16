<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Repository\OrderFilter;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\ValueObject\Status;
use App\Shared\Domain\Repository\PaginationResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Constructs a new OrderRepository instance.
 *
 * @param ManagerRegistry $registry the manager registry
 */
final class OrderRepository extends ServiceEntityRepository implements OrderRepositoryInterface
{
    /**
     * Constructs a new OrderRepository instance.
     *
     * @param ManagerRegistry $registry the manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * Saves an Orders entity to the database.
     *
     * @param Order $order the Orders entity to save
     */
    public function save(Order $order): void
    {
        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds an Orders entity by its ID.
     *
     * @param int $id the ID of the Orders entity
     *
     * @return Order|null the Orders entity matching the given ID, or null if no match found
     */
    public function findById(int $id): ?Order
    {
        return $this->find($id);
    }

    /**
     * Finds Orders entities by their status.
     *
     * @param Status $status the status of the Orders entities
     *
     * @return Order[] the array of Orders entities matching the given status
     */
    public function findByStatus(Status $status): array
    {
        $qb = $this->createQueryBuilder('o');

        $qb->where('o.status = :status')
            ->setParameter('status', $status->value);
        $query = $qb->getQuery();

        return $query->getResult();
    }

    /**
     * Finds Orders entities by Roll ID.
     *
     * @param int $rollId the ID of the Roll
     *
     * @return Order[] an array of Orders entities matching the given Roll ID
     */
    public function findByRollId(int $rollId): array
    {
        $qb = $this->getEntityManager()->createQuery("SELECT o FROM App\Orders\Domain\Aggregate\Order o JOIN App\Orders\Domain\Aggregate\Roll r WITH o MEMBER OF r.orders WHERE r.id = :rollId");

        $qb->setParameter('rollId', $rollId);

        return $qb->getResult();
    }

    /**
     * Finds paginated results.
     *
     * @return PaginationResult the paginated results
     */
    public function findByFilter(OrderFilter $filter): PaginationResult
    {
        $qb = $this->createQueryBuilder('o');

        if ($filter->rollId) {
            $qb->join('App\Orders\Domain\Aggregate\Roll', 'r', \Doctrine\ORM\Query\Expr\Join::WITH, 'o MEMBER OF r.orders')
                ->andWhere('r.id = :rollId')
                ->setParameter('rollId', $filter->rollId);
        }

        //        if ($filter->status) {
        //            $qb->andWhere('o.status = :status')
        //                ->setParameter('status', $filter->status->value);
        //        }

        $query = $qb->getQuery();
        $paginator = new Paginator($query);

        return new PaginationResult($query->getResult(), $paginator->count());
    }
}
