<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Roll\History\History;
use App\Orders\Domain\Repository\HistoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class HistoryRepository.
 */
class HistoryRepository extends ServiceEntityRepository implements HistoryRepositoryInterface
{
    /**
     * Constructs a new OrderRepository instance.
     *
     * @param ManagerRegistry $registry the manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }

    /**
     * Saves a History entity.
     *
     * @param History $history The History entity to be saved
     */
    public function add(History $history): void
    {
        $this->getEntityManager()->persist($history);
        $this->getEntityManager()->flush();
    }

    /**
     * Saves multiple History entities.
     *
     * @param iterable<History> $histories a collection of History objects to be saved
     */
    public function saveMany(iterable $histories): void
    {
        foreach ($histories as $history) {
            $this->getEntityManager()->persist($history);
        }

        $this->getEntityManager()->flush();
    }
    /**
     * Finds a History entity by a rollId.
     *
     * @param int $rollId the rollId to search for
     *
     * @return History[] the found History entity or null if no History entity was found
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByRollId(int $rollId): array
    {
        $query = $this->createQueryBuilder('h');

        $query->where('h.rollId = :rollId')
            ->setParameter('rollId', $rollId)
            ->orderBy('h.happenedAt', 'ASC')
        ;

        return $query->getQuery()->getResult();
    }

    /**
     * Deletes a History entity from the database.
     */
    public function delete(History $history): void
    {
        $this->getEntityManager()->remove($history);
        $this->getEntityManager()->flush();
    }
}
