<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Repository\OrderFilter;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
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
     * Finds paginated results.
     *
     * @return PaginationResult the paginated results
     */
    public function findByFilter(OrderFilter $filter): PaginationResult
    {
        $qb = $this->createQueryBuilder('o');

        if ($filter->perPage) {
            $qb->setMaxResults($filter->perPage);
        }

        if ($filter->page) {
            $qb->setFirstResult($filter->perPage * ($filter->page - 1));
        }

        $query = $qb->getQuery();

        $paginator = new Paginator($query);

        return new PaginationResult($query->getResult(), $paginator->count());
    }
}
