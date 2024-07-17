<?php

namespace App\Rolls\Infrastructure\Repository;

use App\Rolls\Domain\Aggregate\Roll\Roll;
use App\Rolls\Domain\Repository\RollRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RollRepository extends ServiceEntityRepository implements RollRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Roll::class);
    }

    public function add(Roll $roll): void
    {
        $this->getEntityManager()->persist($roll);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?Roll
    {
        return $this->find($id);
    }

    public function save(Roll $roll): void
    {
        $this->getEntityManager()->persist($roll);
        $this->getEntityManager()->flush();
    }

    public function remove(Roll $roll): void
    {
        $this->getEntityManager()->remove($roll);
        $this->getEntityManager()->flush();
    }
}
