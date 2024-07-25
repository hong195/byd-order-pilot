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
     * Creates a new instance of MediaFile with the specified parameters.
     *
     * @param string      $filename  the filename of the media file
     * @param string      $source    the source of the media file
     * @param string      $path      the path of the media file
     * @param string|null $type      the type of the media file (optional)
     * @param int|null    $ownerId   the owner ID of the media file (optional)
     * @param string|null $ownerType the owner type of the media file (optional)
     *
     * @return MediaFile a new instance of MediaFile
     */
    public function make(string $filename, string $source, string $path, ?string $type = null, ?int $ownerId = null, ?string $ownerType = null): MediaFile
    {
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
