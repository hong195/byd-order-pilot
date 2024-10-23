<?php

namespace App\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Repository\PrintedProductFilter;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository class for Roll entities.
 * Extends the ServiceEntityRepository class and implements the RollRepositoryInterface.
 */
class PrintedProductRepository extends ServiceEntityRepository implements PrintedProductRepositoryInterface
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry the manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrintedProduct::class);
    }

    /**
     * Adds a PrintedProduct to the database.
     *
     * @param PrintedProduct $printedProduct the PrintedProduct object to be added
     */
    public function add(PrintedProduct $printedProduct): void
    {
        $this->getEntityManager()->persist($printedProduct);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds a PrintedProduct by its ID.
     *
     * @param int $id the ID of the PrintedProduct
     *
     * @return PrintedProduct|null the found PrintedProduct object, or null if no PrintedProduct was found
     */
    public function findById(int $id): ?PrintedProduct
    {
        return $this->find($id);
    }

    /**
     * Saves a PrintedProduct object.
     *
     * @param PrintedProduct $printedProduct the PrintedProduct object to be saved
     */
    public function save(PrintedProduct $printedProduct): void
    {
        $this->getEntityManager()->persist($printedProduct);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds rolls by roll type.
     *
     * @return PrintedProduct[] an array of rolls matching the roll type
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
     * Finds PrintedProducts by Roll ID.
     *
     * @return PrintedProduct[] an array of PrintedProduct objects matching the Roll ID
     */
    public function findByFilter(PrintedProductFilter $filter): array
    {
        $qb = $this->createQueryBuilder('p');

        if ($filter->rollId) {
            $qb->innerJoin('p.roll', 'r');
            $qb->andWhere('r.id = :rollId')
                ->setParameter('rollId', $filter->rollId);
        }

        if ($filter->unassigned) {
            $qb->andWhere('p.roll IS NULL');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Finds an array of PrintedProducts by their IDs.
     *
     * @param iterable<int> $relatedProductsIds The array of IDs to find PrintedProducts for
     *
     * @return PrintedProduct[] An array of PrintedProduct objects corresponding to the provided IDs
     */
    public function findByRelatedProductIds(iterable $relatedProductsIds): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where('p.relatedProductId IN (:ids)')
            ->setParameter('ids', $relatedProductsIds);

        return $qb->getQuery()->getResult();
    }
}
