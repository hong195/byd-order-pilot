<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Events;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\EventType;

final readonly class FilmWasUsedEvent implements EventInterface, EventHasNameInterface
{
    public function __construct(public string $filmId, public float $newSize, public float $oldSize)
    {
    }

    public function __toString(): string
    {
        return 'film_was_used';
    }

    public function getEventType(): string
    {
        return EventType::FILM_WAS_USED;
    }
}
