<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\Repository;

use App\Inventory\Domain\Aggregate\History;
use App\Inventory\Domain\Repository\HistoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @param int $filmId the film id to search for
     *
     * @return array an array of records
     */
    public function findByFilmId(int $filmId): array
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
}
