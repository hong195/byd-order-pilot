<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Events;

use App\Shared\Domain\Event\EventInterface;

final readonly class FilmWasCreatedEvent implements EventInterface, EventHasNameInterface
{
    public function __construct(public int $filmId)
    {
    }

    public function __toString(): string
    {
        return 'film_length_was_created';
    }
}
