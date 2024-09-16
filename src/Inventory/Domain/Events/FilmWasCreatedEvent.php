<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Events;

use App\Shared\Domain\Event\EventInterface;

final readonly class FilmWasCreatedEvent implements EventInterface
{
    public function __construct(public int $filmId)
    {
    }
}
