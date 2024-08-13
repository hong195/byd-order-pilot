<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Printer;
use App\Orders\Domain\Repository\PrinterRepositoryInterface;
use App\Orders\Domain\ValueObject\RollType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PrinterRepository extends ServiceEntityRepository implements PrinterRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Printer::class);
    }

    /**
     * Saves a Printer entity to the database.
     *
     * @param Printer $printer the Printer entity to be saved
     */
    public function save(Printer $printer): void
    {
        $this->getEntityManager()->persist($printer);
        $this->getEntityManager()->flush();
    }

    /**
     * Returns an array of entities based on the given names.
     *
     * @param string[] $names the names to search for
     *
     * @return Printer[] the array of entities matching the given names
     */
    public function findByNames(array $names): array
    {
        return $this->findBy(['name' => $names]);
    }

    /**
     * Finds a Printer entity by RollType.
     *
     * @param RollType $rollType the RollType entity to search for in Printer entities
     *
     * @return Printer|null the found Printer entity or null if not found
     */
    public function findByRollType(RollType $rollType): ?Printer
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder->where('JSONB_CONTAINS(p.rollTypes, :rollType) = true')
            ->setParameter('rollType', json_encode($rollType->value));

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
