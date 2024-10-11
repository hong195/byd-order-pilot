<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repository;

use App\Shared\Domain\Entity\MediaFile;
use App\Shared\Domain\Repository\MediaFileRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MediaFileRepository extends ServiceEntityRepository implements MediaFileRepositoryInterface
{
    /**
     * Constructor for the class.
     *
     * @param ManagerRegistry $registry The Symfony Doctrine ManagerRegistry instance
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaFile::class);
    }

    /**
     * Saves a media file to the database.
     *
     * @param MediaFile $mediaFile the media file to be saved
     */
    public function save(MediaFile $mediaFile): void
    {
        $this->getEntityManager()->persist($mediaFile);
        $this->getEntityManager()->flush();
    }

    /**
     * Removes a media file from the database.
     *
     * @param MediaFile $mediaFile the media file to be removed
     */
    public function remove(MediaFile $mediaFile): void
    {
        $this->getEntityManager()->remove($mediaFile);
    }

    /**
     * Find a media file by its ID.
     *
     * @param int $id the ID of the media file to find
     *
     * @return ?MediaFile the media file with the given ID, or null if not found
     */
    public function findById(int $id): ?MediaFile
    {
        return $this->find($id);
    }

    /**
     * Find media files by their owner IDs.
     *
     * @param array $ids an array of owner IDs
     *
     * @return array an array of media files that belong to the specified owner IDs
     */
    public function findByOwnerIds(array $ids): array
    {
        $qb = $this->createQueryBuilder('m')
			->where('m.ownerId IN (:ids)')
			->setParameter('ids', $ids)
			->getQuery();

		return $qb->getResult();
    }
}
