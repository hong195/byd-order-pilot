<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Repository;

use App\Orders\Domain\Aggregate\Printer;
use App\Orders\Domain\Repository\PrinterFilter;
use App\Orders\Domain\Repository\PrinterRepositoryInterface;
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
     * Find queried printers based on given filter.
     *
     * @param PrinterFilter $printerFilter The filter to apply on printers
     *
     * @return Printer[] An array of queried printers
     */
    public function findQueried(PrinterFilter $printerFilter): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        if ($printerFilter->rollTypes) {
            $queryBuilder->where('JSONB_CONTAINS(p.rollTypes, :rollType) = 1')
                ->setParameter('rollType', json_encode($printerFilter->rollTypes));
        }

        if ($printerFilter->laminationTypes) {
            $queryBuilder->andWhere('JSONB_CONTAINS(p.laminationTypes, :laminationType) = 1')
                ->setParameter('laminationType', json_encode($printerFilter->laminationTypes));
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
