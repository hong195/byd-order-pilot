<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Product;
use App\Orders\Domain\Repository\ProductFilter;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * Returns a single entity by the given productId.
     *
     * @param int $productId the productId to filter the entity
     *
     * @return ?Product the entity filtered by the productId
     */
    public function findById(int $productId): ?Product
    {
        return $this->find(['id' => $productId]);
    }

    /**
     * Returns a collection of entities based on the given filter.
     *
     * @param ProductFilter $filter the filter object containing criteria for entity search
     *
     * @return Collection<Product> the collection of entities matching the filter
     */
    public function findByFilter(ProductFilter $filter): Collection
    {
        $qb = $this->createQueryBuilder('p');

        if ($filter->orderId) {
            $qb->join('p.order', 'o')
                ->andWhere('o.id = :orderId')
                ->setParameter('orderId', $filter->orderId);
        }

        if ($filter->productIds) {
            $qb->andWhere('p.id IN (:productIds)')
                ->setParameter('productIds', $filter->productIds);
        }

        return new ArrayCollection($qb->getQuery()->getResult());
    }
}
