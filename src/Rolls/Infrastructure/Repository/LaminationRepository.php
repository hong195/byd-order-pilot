<?php

namespace App\Rolls\Infrastructure\Repository;

use App\Rolls\Domain\Aggregate\Lamination\Lamination;
use App\Rolls\Domain\Repository\LaminationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
}
