<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate;

use App\Shared\Domain\Event\EventInterface;

abstract class AggregateRoot
{
    /**
     * @var EventInterface[]
     */
    private array $events = [];

    abstract public function getId(): string;

    /**
     * @return EventInterface[]
     */
    public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    public function eventsEmpty(): bool
    {
        return empty($this->events);
    }

    protected function raise(EventInterface $event): void
    {
        $this->events[] = $event;
    }
}
