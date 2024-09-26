<?php

namespace App\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
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
	 * @param PrintedProduct $printedProduct The PrintedProduct object to be added.
	 *
	 * @return void
	 */
    public function add(PrintedProduct $printedProduct): void
    {
        $this->getEntityManager()->persist($printedProduct);
        $this->getEntityManager()->flush();
    }

	/**
	 * Finds a PrintedProduct by its ID.
	 *
	 * @param int $id The ID of the PrintedProduct.
	 *
	 * @return PrintedProduct|null The found PrintedProduct object, or null if no PrintedProduct was found.
	 */
    public function findById(int $id): ?PrintedProduct
    {
        return $this->find($id);
    }

	/**
	 * Saves a PrintedProduct object.
	 *
	 * @param PrintedProduct $printedProduct The PrintedProduct object to be saved.
	 *
	 * @return void
	 */
    public function save(PrintedProduct $printedProduct): void
    {
        $this->getEntityManager()->persist($printedProduct);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds rolls by roll type.
     *
     * @return Roll[] an array of rolls matching the roll type
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
}
