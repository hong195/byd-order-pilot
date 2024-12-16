<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Infrastructure\Event\DomainEventProducer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function __construct(ManagerRegistry $registry, private DomainEventProducer $domainEventProducer)
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

        $this->domainEventProducer->produce(...$order->pullEvents());
    }

    /**
     * Finds an Orders entity by its ID.
     *
     * @param string $id the ID of the Orders entity
     *
     * @return Order|null the Orders entity matching the given ID, or null if no match found
     */
    public function findById(string $id): ?Order
    {
        return $this->find($id);
    }

    /**
     * Finds Orders ready for packing.
     *
     * @return array An array of Orders entities that are ready for packing
     */
    public function findPacked(): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.products', 'p')
            ->groupBy('o.id')
            ->having('COUNT(p.id) = SUM(CASE WHEN p.isPacked = true THEN 1 ELSE 0 END)')
            ->getQuery()
            ->getResult();
    }

    /**
     * Finds Orders that are partially or not packed.
     *
     * @return array An array of Orders entities that are partially or not packed
     */
    public function findPartiallyPacked(): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.products', 'p')
            ->where('p.id IS NOT NULL')
            ->andWhere('p.isPacked = false')
            ->groupBy('o.id')
            ->getQuery()
            ->getResult();
    }

    /**
     * Finds entities only with extras.
     *
     * @return array An array of entities that have extras
     */
    public function findOnlyWithExtras(): array
    {
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.products', 'p')    // Присоединяем обычные продукты
            ->leftJoin('o.extras', 'e')      // Присоединяем экстра-продукты
            ->where('p.id IS NULL')          // Убеждаемся, что продуктов нет
            ->andWhere('e.id IS NOT NULL');  // Убеждаемся, что экстра-продукты есть

        return $qb->getQuery()->getResult();
    }
}
