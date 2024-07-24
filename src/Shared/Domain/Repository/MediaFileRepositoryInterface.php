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
     * Finds a MediaFile by its id.
     *
     * @param int $id the id of the MediaFile
     *
     * @return MediaFile|null the MediaFile object if found, null otherwise
     */
    public function findById(int $id): ?MediaFile;

    /**
     * Save a media file.
     *
     * @param MediaFile $mediaFile the media file object to be saved
     */
    public function save(MediaFile $mediaFile): void;

    /**
     * Find a media file by its id.
     *
     * @param string $id the id of the media file to find
     *
     * @return MediaFile|null the found media file, or null if not found
     */
    public function find(string $id): ?MediaFile;

    /**
     * Removes a MediaFile from the database.
     *
     * @param MediaFile $mediaFile the MediaFile object to be removed
     */
    public function remove(MediaFile $mediaFile): void;
}
