<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\EventHandler;

use App\Inventory\Domain\Events\FilmWasCreatedEvent;
use App\Inventory\Domain\Events\FilmWasDeletedEvent;
use App\Inventory\Domain\Events\FilmWasUpdatedEvent;
use App\Inventory\Infrastructure\Event\EventEnvelope;
use App\Inventory\Infrastructure\Event\ExternalEvent\RollWasSentToPrintCheckInExternalEvent;
use App\Shared\Domain\Event\EventType;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[AsMessageHandler]
final class EventEnvelopeHandler
{
    private const EVENT_MAP = [
        EventType::ROLL_WAS_SENT_TO_PRINT_CHECK_IN => RollWasSentToPrintCheckInExternalEvent::class,
        EventType::FILM_WAS_CREATED => FilmWasCreatedEvent::class,
        EventType::FILM_WAS_DELETED => FilmWasDeletedEvent::class,
        EventType::FILM_WAS_UPDATED => FilmWasUpdatedEvent::class,
    ];

    public function __construct(private DenormalizerInterface $denormalizer, private MessageBusInterface $eventBus)
    {
    }

    /**
     * @throws ExceptionInterface
     * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
     */
    public function __invoke(EventEnvelope $eventEnvelope): void
    {
        $class = self::EVENT_MAP[$eventEnvelope->getEventType()] ?? null;

        if (null === $class) {
            return;
        }

        $domainEvent = $this->denormalizer->denormalize($eventEnvelope->getEventData(), $class);
        $this->eventBus->dispatch($domainEvent);
    }
}
