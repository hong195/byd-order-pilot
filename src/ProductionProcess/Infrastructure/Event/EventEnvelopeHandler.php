<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Event;

use App\Orders\Domain\Event\ProductAddedEvent;
use App\ProductionProcess\Domain\Events\PrintedProductReprintedEvent;
use App\ProductionProcess\Domain\Events\RollProcessWasUpdatedEvent;
use App\ProductionProcess\Domain\Events\RollWasSentToCutCheckInEvent;
use App\ProductionProcess\Domain\Events\RollWasSentToGlowCheckInEvent;
use App\ProductionProcess\Domain\Events\RollWasSentToPrintCheckInEvent;
use App\Shared\Domain\Event\EventType;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[AsMessageHandler]
final class EventEnvelopeHandler
{
    private const EVENT_MAP = [
        EventType::ROLL_WAS_SENT_TO_PRINT_CHECK_IN => RollWasSentToPrintCheckInEvent::class,
        EventType::ROLL_PROCESS_WAS_UPDATED => RollProcessWasUpdatedEvent::class,
        EventType::ROLL_WAS_SENT_TO_CUT_CHECK_IN => RollWasSentToCutCheckInEvent::class,
        EventType::ROLL_WAS_SENT_TO_GLOW_CHECK_IN => RollWasSentToGlowCheckInEvent::class,
        EventType::PRINTED_PRODUCT_REPRINTED => PrintedProductReprintedEvent::class,
        EventType::PRODUCT_CREATED => ProductAddedEvent::class,
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
