<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

use App\Shared\Domain\Entity\MediaFile;

/**
 * Interface MediaFileRepositoryRepository.
 */
interface MediaFileRepositoryInterface
{
    /**
     * Save a media file.
     *
     * @param MediaFile $mediaFile the media file object to be saved
     */
    public function save(MediaFile $mediaFile): void;

    /**
     * Removes a MediaFile from the database.
     *
     * @param MediaFile $mediaFile the MediaFile object to be removed
     */
    public function remove(MediaFile $mediaFile): void;

    /**
     * Finds a media file by its ID.
     *
     * @param string $id the ID of the media file
     *
     * @return MediaFile|null the media file object if found, or null if not found
     */
    public function findById(string $id): ?MediaFile;

    /**
     * Finds media files by their owner IDs.
     *
     * @param int[] $ids an array of owner IDs
     *
     * @return MediaFile[] an array of media file objects that belong to the specified owner IDs
     */
    public function findByOwnerIds(array $ids): array;
}
