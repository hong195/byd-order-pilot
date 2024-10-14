<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Product;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Constructs a new OrderRepository instance.
 *
 * @param ManagerRegistry $registry the manager registry
 */
final class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    /**
     * Constructs a new OrderRepository instance.
     *
     * @param ManagerRegistry $registry the manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Adds a product to the repository.
     *
     * @param Product $product The product to be added
     */
    public function save(Product $product): void
    {
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();
    }

    /**
     * Returns an array of entities filtered by the given orderId.
     *
     * @param int $orderId the orderId to filter the entities
     *
     * @return Product[] an array of entities filtered by the orderId
     */
    public function findByOrderId(int $orderId): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->setParameter('orderId', $orderId);
        $qb->where('p.order = :orderId');

        return $qb->getQuery()->getResult();
    }

    /**
     * Returns a single entity by the given productId.
     *
     * @param int $productId the productId to filter the entity
     *
     * @return Product the entity filtered by the productId
     */
    public function findById(int $productId): Product
    {
        return $this->find(['id' => $productId]);
    }
}
