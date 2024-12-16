<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Events;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\EventType;

final class FilmWasDeletedEvent implements EventInterface, EventHasNameInterface
{
    public float $newSize = 0;

    public function __construct(public readonly string $filmId, public readonly float $oldSize)
    {
    }

    public function __toString(): string
    {
        return 'film_was_removed';
    }

	public function getEventType(): string
	{
		return EventType::FILM_WAS_DELETED;
	}
}
