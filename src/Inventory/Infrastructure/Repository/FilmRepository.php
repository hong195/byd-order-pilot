<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\Repository;

use App\Inventory\Domain\Aggregate\AbstractFilm;
use App\Inventory\Domain\Repository\FilmFilter;
use App\Inventory\Domain\Repository\FilmRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class FilmRepository extends ServiceEntityRepository implements FilmRepositoryInterface
{
    /**
     * Constructor for the class.
     *
     * @param ManagerRegistry $entityManager the entity manager for the application
     *
     * @return void
     */
    public function __construct(ManagerRegistry $entityManager)
    {
        parent::__construct($entityManager, AbstractFilm::class);
    }

    /**
     * Saves the given film to the database.
     *
     * @param AbstractFilm $film the film to be saved
     */
    public function save(AbstractFilm $film): void
    {
        $this->getEntityManager()->persist($film);
        $this->getEntityManager()->flush();
    }

    /**
     * Removes a film from the database.
     *
     * @param AbstractFilm $film the film to be removed
     */
    public function remove(AbstractFilm $film): void
    {
        $this->getEntityManager()->remove($film);
        $this->getEntityManager()->flush();
    }

    /**
     * Find a film by its ID.
     *
     * @param int $id the ID of the film to find
     *
     * @return AbstractFilm|null the found film entity, or null if not found
     */
    public function findById(int $id): ?AbstractFilm
    {
        return $this->find($id);
    }

    /**
     * Find all films.
     *
     * @return AbstractFilm[] an array of all film entities
     */
    public function findQueried(FilmFilter $filmFilter): array
    {
        $dql = 'SELECT f FROM App\Inventory\Domain\Aggregate\AbstractFilm f WHERE f INSTANCE OF :filmType ORDER BY f.length ASC';

        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('filmType', $filmFilter->filmType);

        return $query->getResult();
    }
}
