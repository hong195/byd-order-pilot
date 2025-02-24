<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Repository;

use App\ProductionProcess\Domain\Aggregate\Printer\Printer;
use App\ProductionProcess\Domain\Repository\Printer\PrinterRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
     * Finds a printer by its ID.
     *
     * @param string $printerId the ID of the printer to find
     *
     * @return Printer|null the found printer object, or null if no printer was found
     */
    public function findById(string $printerId): ?Printer
    {
        return $this->find($printerId);
    }

    /**
     * Finds all printers.
     *
     * @return ArrayCollection an array containing all the printer objects
     */
    public function all(): ArrayCollection
    {
        $queryBuilder = $this->createQueryBuilder('p');

        return new ArrayCollection($queryBuilder->getQuery()->getResult());
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
