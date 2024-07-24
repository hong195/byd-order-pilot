<?php

declare(strict_types=1);

namespace App\Shared\Domain\Factory;

use App\Shared\Domain\Entity\MediaFile;

/**
 * MediaFileFactory class is responsible for creating instances of the MediaFile class.
 */
final readonly class MediaFileFactory
{
    /**
     * Creates a new instance of the MediaFile class.
     *
     * @param string $filename  the filename of the media file
     * @param string $source    the source of the media file
     * @param string $path      the path of the media file
     * @param string $type      the type of the media file
     * @param int    $ownerId   the ID of the owner of the media file
     * @param string $ownerType the type of the owner of the media file
     *
     * @return MediaFile a new instance of the MediaFile class
     */
    public function make(
        string $filename,
        string $source,
        string $path,
        string $type,
        int $ownerId,
        string $ownerType
    ): MediaFile {
        return new MediaFile(
            filename: $filename,
            source: $source,
            path: $path,
            type: $type,
            ownerId: $ownerId,
            ownerType: $ownerType
        );
    }
}
