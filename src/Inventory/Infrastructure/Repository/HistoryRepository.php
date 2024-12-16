<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\Repository;

use App\Inventory\Domain\Aggregate\History;
use App\Inventory\Domain\Repository\HistoryFilter;
use App\Inventory\Domain\Repository\HistoryRepositoryInterface;
use App\Shared\Domain\Repository\PaginationResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

final class HistoryRepository extends ServiceEntityRepository implements HistoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }

    /**
     * Adds a History record to the database.
     *
     * @param History $history the History record to add
     */
    public function add(History $history): void
    {
        $this->getEntityManager()->persist($history);
        $this->getEntityManager()->flush();
    }

    /**
     * Returns an array of records based on the given film id.
     *
     * @param string $filmId the film id to search for
     *
     * @return array an array of records
     */
    public function findByFilmId(string $filmId): array
    {
        return $this->findBy(['filmId' => $filmId]);
    }

    /**
     * Finds entities by film type.
     *
     * @param string $filmType the film type to search for
     *
     * @return array the found entities
     */
    public function findByFilmType(string $filmType): array
    {
        return $this->findBy(['filmType' => $filmType]);
    }

    /**
     * Finds entities by filter.
     *
     * @param HistoryFilter $filter
     *
     * @return PaginationResult the found entities
     *
     * @throws \Exception
     */
    public function findByFilter(HistoryFilter $filter): PaginationResult
    {
        $qb = $this->createQueryBuilder('h');

        if ($filter->inventoryType) {
            $qb->andWhere('h.inventoryType = :inventoryType')
                ->setParameter('inventoryType', $filter->inventoryType);
        }

        if ($filter->filmId) {
            $qb->andWhere('h.filmId = :filmId')
                ->setParameter('filmId', $filter->filmId);
        }

        if ($filter->event) {
            $qb->andWhere('h.eventType = :event')
                ->setParameter('event', $filter->event);
        }

        if ($filter->type) {
            $qb->andWhere('h.filmType = :filmType')
                ->setParameter('filmType', $filter->type);
        }

        if ($filter->interval) {
            $start = $filter->interval[0];
            $end = $filter->interval[1];

            $qb->andWhere('h.createdAt >= :startDate')
                ->setParameter('startDate', $start);

            $qb->andWhere('h.createdAt <= :endDate')
                ->setParameter('endDate', $end);
        }

		if ($filter->pager) {
			$qb->setMaxResults($filter->pager->getLimit());
			$qb->setFirstResult($filter->pager->getOffset());
		}

        $paginator = new Paginator($qb->getQuery());

        return new PaginationResult(
            iterator_to_array($paginator->getIterator()),
            $paginator->count()
        );
    }
}
