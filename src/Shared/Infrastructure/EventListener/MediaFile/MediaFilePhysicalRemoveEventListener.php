<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventListener\MediaFile;

use App\Shared\Domain\Event\MediaFilePhysicalRemoveEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

#[AsEventListener(event: MediaFilePhysicalRemoveEvent::class, method: '__invoke')]
readonly class MediaFilePhysicalRemoveEventListener {
    public function __construct(private Filesystem $filesystem)
    {
    }

    public function __invoke(string $mediaFilePath): void
    {
        try {
            if ($this->filesystem->exists($mediaFilePath)) {
                $this->filesystem->remove($mediaFilePath);
            }
        } catch (IOException $e) {
            throw new \RuntimeException(sprintf('Could not delete file: %s', $mediaFilePath));
        }
    }
}