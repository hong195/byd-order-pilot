<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Events;

use App\Shared\Domain\Event\EventInterface;

final readonly class FilmLengthWasUpdatedEvent implements EventInterface, EventHasNameInterface
{
    public function __construct(public int $filmId, public float $newSize, public float $oldSize)
    {
    }

	public function __toString(): string
	{
		return 'film_length_was_updated';
	}
}
