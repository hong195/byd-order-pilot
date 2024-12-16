<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Events;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\EventType;

final class FilmWasDeletedEvent implements EventInterface
{
    public float $newSize = 0;

    public function __construct(public readonly string $filmId, public readonly float $oldSize, public readonly string $filmType)
    {
    }

    public function getEventType(): string
    {
        return EventType::FILM_WAS_DELETED;
    }
}
