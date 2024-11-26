<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventListener\MediaFile;

use App\Shared\Domain\Event\MediaFileRemovedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Listener for MediaFileRemovedEvent.
 */
#[AsEventListener(event: MediaFileRemovedEvent::class, method: '__invoke')]
readonly class MediaFileRemovedListener
{
    /**
     * @param Filesystem $filesystem
     */
    public function __construct(private Filesystem $filesystem)
    {
    }

    /**
     * @param MediaFileRemovedEvent $event
     *
     * @return void
     */
    public function __invoke(MediaFileRemovedEvent $event): void
    {
        $path = $event->mediaFilePath;

        try {
            if ($this->filesystem->exists($path)) {
                $this->filesystem->remove($path);
            }
        } catch (IOException $e) {
            throw new \RuntimeException(sprintf('Could not delete file: %s', $path));
        }
    }
}
