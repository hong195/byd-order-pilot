<?php

declare(strict_types=1);

namespace App\Rolls\Infrastructure\Repository;

use App\Rolls\Domain\Aggregate\Order\Order;
use App\Rolls\Domain\Repository\OrderRepositoryInterface;
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
     * Saves an Order entity to the database.
     *
     * @param Order $order the Order entity to save
     */
    public function save(Order $order): void
    {
        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds an Order entity by its ID.
     *
     * @param int $id the ID of the Order entity
     *
     * @return Order|null the Order entity matching the given ID, or null if no match found
     */
    public function findById(int $id): ?Order
    {
        return $this->find($id);
    }

    /**
     * Finds paginated results.
     *
     * @return PaginationResult the paginated results
     */
    public function findQueried(): PaginationResult
    {
        $qb = $this->createQueryBuilder('o');

        $query = $qb->getQuery();

        $query->setMaxResults(100);
        $paginator = new Paginator($query);

        return new PaginationResult($query->getResult(), $paginator->count());
    }
}
