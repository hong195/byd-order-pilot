<?php

namespace App\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductFilter;
use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @param string $id the ID of the PrintedProduct
     *
     * @return PrintedProduct|null the found PrintedProduct object, or null if no PrintedProduct was found
     */
    public function findById(string $id): ?PrintedProduct
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
    public function findUnassign(): array
    {
        $qb = $this->createQueryBuilder('r');

        $qb->where('r.roll IS NULL');

        $query = $qb->getQuery();

        return $query->getResult();
    }

    /**
     * Finds PrintedProducts by Roll ID.
     *
	 * @return Collection<PrintedProduct> the result of the pagination
     */
    public function findByFilter(PrintedProductFilter $filter): Collection
    {
        $qb = $this->createQueryBuilder('p');

        if ($filter->rollId) {
            $qb->innerJoin('p.roll', 'r');
            $qb->andWhere('r.id = :rollId')
                ->setParameter('rollId', $filter->rollId);
        }

		if (!empty($filter->ids)) {
			$qb->andWhere('p.id IN (:ids)')
				->setParameter('ids', $filter->ids);
		}

        if ($filter->unassigned) {
            $qb->andWhere('p.roll IS NULL');
        }

        return new ArrayCollection($qb->getQuery()->getResult());
    }

    /**
     * Finds an array of PrintedProducts by their IDs.
     *
     * @param iterable<string> $relatedProductsIds The array of IDs to find PrintedProducts for
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
