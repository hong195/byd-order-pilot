<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Events;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\EventType;

final readonly class FilmWasCreatedEvent implements EventInterface
{
    public function __construct(public string $filmId)
    {
    }

    public function getEventType(): string
    {
        return EventType::FILM_WAS_CREATED;
    }
}
