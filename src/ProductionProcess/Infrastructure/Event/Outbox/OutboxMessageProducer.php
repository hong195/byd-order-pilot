<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Event\Outbox;

use App\Shared\Domain\Event\EventInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class OutboxMessageProducer
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function produce(EventInterface ...$events): void
    {
        foreach ($events as $event) {
            $message = new OutboxMessage($event);
            $this->bus->dispatch($message);
        }
    }
}
