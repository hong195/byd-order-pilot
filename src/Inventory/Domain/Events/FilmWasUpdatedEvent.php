<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Events;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\EventType;

final readonly class FilmWasUpdatedEvent implements EventInterface, EventHasNameInterface
{
    /**
     * Constructor for FilmLengthUpdateEvent.
     *
     * @param string   $filmId  the ID of the film
     * @param float $newSize the new size of the film
     * @param float $oldSize the old size of the film
     */
    public function __construct(public string $filmId, public float $newSize, public float $oldSize)
    {
    }

    /**
     * Get the string representation of the object.
     *
     * @return string the string representation of the object
     */
    public function __toString(): string
    {
        return 'film_length_was_updated';
    }

	public function getEventType(): string
	{
		return EventType::FILM_WAS_UPDATED;
	}
}
