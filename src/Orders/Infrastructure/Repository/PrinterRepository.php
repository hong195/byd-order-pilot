<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Printer;
use App\Orders\Domain\Repository\PrinterRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PrinterRepository extends ServiceEntityRepository implements PrinterRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Printer::class);
    }

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
}
