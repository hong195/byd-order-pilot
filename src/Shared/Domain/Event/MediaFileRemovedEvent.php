<?php

declare(strict_types=1);

/**
 * MediaFileRemovedEvent represents an event that is triggered when a media file is removed.
 *
 * Implements the EventInterface to ensure compatibility with the event handling system.
 *
 * @param string $mediaFilePath the path of the media file being removed
 */

namespace App\Shared\Domain\Event;

/**
 * Event to trigger media file remove.
 */
final readonly class MediaFileRemovedEvent implements EventInterface
{
    public function __construct(public string $mediaFilePath)
    {
    }

    /**
     * Returns the path of the media file being removed.
     */
    public function getEventType(): string
    {
        return EventType::FILE_WAS_REMOVED;
    }
}
