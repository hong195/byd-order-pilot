<?php

namespace App\Rolls\Infrastructure\Repository;

use App\Rolls\Domain\Aggregate\Lamination\Lamination;
use App\Rolls\Domain\Repository\LaminationRepositoryInterface;
use App\Shared\Domain\Repository\PaginationResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lamination>
 */
class LaminationRepository extends ServiceEntityRepository implements LaminationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lamination::class);
    }

    public function add(Lamination $lamination): void
    {
        $this->getEntityManager()->persist($lamination);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?Lamination
    {
        return $this->find($id);
    }

    public function save(Lamination $lamination): void
    {
        $this->getEntityManager()->persist($lamination);
        $this->getEntityManager()->flush();
    }

    public function remove(Lamination $lamination): void
    {
        $this->getEntityManager()->remove($lamination);
        $this->getEntityManager()->flush();
    }

	/**
	 * Find paged items
	 *
	 * @param int $page The current page number
	 * @return PaginationResult The pagination result object
	 */
	public function findPagedItems(int $page = 1): PaginationResult
    {
        $qb = $this->createQueryBuilder('l');

        $query = $qb->getQuery();

        $query->setMaxResults(10);
        $paginator = new Paginator($query);

        return new PaginationResult($query->getResult(), $paginator->count());
    }
}
