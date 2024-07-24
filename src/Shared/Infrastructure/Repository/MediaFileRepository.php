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
     * Finds a MediaFile by its ID.
     *
     * @param int $id the ID of the MediaFile to find
     *
     * @return MediaFile|null the MediaFile object if found, null otherwise
     */
    public function findById(int $id): ?MediaFile
    {
        return $this->find($id);
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
}
