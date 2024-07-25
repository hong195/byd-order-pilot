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
     * @param int $id the ID of the media file
     *
     * @return MediaFile|null the media file object if found, or null if not found
     */
    public function findById(int $id): ?MediaFile;
}
