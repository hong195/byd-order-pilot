<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\Event;

use Symfony\Component\Uid\Ulid;

class EventEnvelope
{
    public string $eventId;

    public int $eventTime;
    public string $eventType;

    /**
     * @mixed $eventData
     */
    public array $eventData;
    public array $headers;

    public function __construct(
        string $eventType,
        array $eventData,
        array $headers = [],
    ) {
        $this->eventId = Ulid::generate();
        $this->eventTime = time();
        $this->eventType = $eventType;
        $this->eventData = $eventData;
        $this->headers = $headers;
    }

    /**
     * Retrieves the event ID.
     *
     * @return string the event ID
     */
    public function getEventId(): string
    {
        return $this->eventId;
    }

    /**
     * Retrieves the event type.
     *
     * @return string The event type
     */
    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function getEventTime(): int
    {
        return $this->eventTime;
    }

    /**
     * Retrieves the event data.
     *
     * @return mixed the event data
     */
    public function getEventData(): array
    {
        return $this->eventData;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
