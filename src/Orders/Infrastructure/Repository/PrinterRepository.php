<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Printer;
use App\Orders\Domain\Repository\PrinterRepositoryInterface;
use App\Orders\Domain\ValueObject\FilmType;
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
     * Finds a printer by its ID.
     *
     * @param int $printerId the ID of the printer to find
     *
     * @return Printer|null the found printer object, or null if no printer was found
     */
    public function findById(int $printerId): ?Printer
    {
        return $this->find($printerId);
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
     * Finds a Printer entity by filmType.
     *
     * @param FilmType $filmType the filmType entity to search for in Printer entities
     *
     * @return Printer|null the found Printer entity or null if not found
     */
    public function findByfilmType(FilmType $filmType): ?Printer
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder->where('JSONB_CONTAINS(p.filmTypes, :filmType) = true')
            ->setParameter('filmType', json_encode($filmType->value));

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
