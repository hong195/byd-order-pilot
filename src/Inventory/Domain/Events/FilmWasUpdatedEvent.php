<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Events;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\EventType;

final readonly class FilmWasUpdatedEvent implements EventInterface
{
    /**
     * Constructor for FilmLengthUpdateEvent.
     *
     * @param string $filmId  the ID of the film
     * @param float  $newSize the new size of the film
     * @param float  $oldSize the old size of the film
     */
    public function __construct(public string $filmId, public float $newSize, public float $oldSize, public string $filmType)
    {
    }

    public function getEventType(): string
    {
        return EventType::FILM_WAS_UPDATED;
    }
}
