<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Event;

use App\Shared\Domain\Event\EventInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DomainEventProducer
{
    public function __construct(private MessageBusInterface $eventBus, private NormalizerInterface $normalizer)
    {
    }

    /**
     * @throws ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function produce(EventInterface ...$events): void
    {
        foreach ($events as $event) {
            $event = $this->wrapDomainEvent($event);
            $stamps = [new AmqpStamp($event->getEventType())];

            $this->eventBus->dispatch($event, $stamps);
        }
    }

    /**
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    private function wrapDomainEvent(EventInterface $event): EventEnvelope
    {
        return new EventEnvelope(
            $event->getEventType(),
            $this->normalizer->normalize($event)
        );
    }
}
