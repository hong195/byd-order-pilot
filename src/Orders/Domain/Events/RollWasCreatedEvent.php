<?php

declare(strict_types=1);

namespace App\Orders\Domain\Events;

use App\Shared\Domain\Event\EventInterface;

final readonly class RollWasCreatedEvent implements EventInterface
{
    public function __construct(public int $rollId)
    {
    }
}
