<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Extra;
use App\Orders\Domain\Repository\ExtraRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Constructs a new OrderRepository instance.
 *
 * @param ManagerRegistry $registry the manager registry
 */
final class ExtraRepository extends ServiceEntityRepository implements ExtraRepositoryInterface
{
    /**
     * Constructs a new OrderRepository instance.
     *
     * @param ManagerRegistry $registry the manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Extra::class);
    }

    /**
     * Adds an Extra object to the database.
     *
     * @param Extra $extra The Extra object to be added
     */
    public function add(Extra $extra): void
    {
        $this->getEntityManager()->persist($extra);
        $this->getEntityManager()->flush();
    }
}
