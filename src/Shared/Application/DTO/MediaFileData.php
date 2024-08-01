<?php

declare(strict_types=1);

namespace App\Shared\Application\DTO;

final readonly class MediaFileData
{
    public function __construct(
        public int $id,
        public string $filename,
        public string $source,
        public string $path,
        public string $type,
        public int $ownerId,
        public string $ownerType
    ) {
    }
}
