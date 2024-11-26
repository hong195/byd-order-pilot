<?php

declare(strict_types=1);

/**
 * Service for removing media files and dispatching corresponding events.
 */

namespace App\Shared\Domain\Service;

use App\Shared\Domain\Event\MediaFileRemovedEvent;
use App\Shared\Domain\Repository\MediaFileRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Service for removing media files and dispatching corresponding events.
 */
final readonly class RemoveMediaFileService
{
    /**
     * @param EventDispatcherInterface     $eventDispatcher
     * @param MediaFileRepositoryInterface $mediaFileRepository
     */
    public function __construct(private EventDispatcherInterface $eventDispatcher, private MediaFileRepositoryInterface $mediaFileRepository)
    {
    }

    /**
     * Removes the specified media file and dispatches a MediaFileRemovedEvent.
     *
     * @param int $mediaFileId
     */
    public function removePhoto(int $mediaFileId): void
    {
        $mediaFile = $this->mediaFileRepository->findById($mediaFileId);

        $path = $mediaFile->getPath();

        $this->mediaFileRepository->remove($mediaFile);

        $this->eventDispatcher->dispatch(new MediaFileRemovedEvent($path));
    }
}
