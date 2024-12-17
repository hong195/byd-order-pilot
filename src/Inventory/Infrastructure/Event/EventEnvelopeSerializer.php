<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\Event;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;

class EventEnvelopeSerializer implements SerializerInterface
{
    final public function __construct(private SymfonySerializerInterface $serializer)
    {
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        if (empty($encodedEnvelope['body'])) {
            throw new MessageDecodingFailedException('Encoded envelope should have body');
        }

        try {
            /** @var EventEnvelope $message */
            $message = $this->serializer->deserialize($encodedEnvelope['body'], EventEnvelope::class, 'json');
        } catch (ExceptionInterface $e) {
            throw new MessageDecodingFailedException('Could not decode message: '.$e->getMessage(), $e->getCode(), $e);
        }

        return new Envelope($message);
    }

    public function encode(Envelope $envelope): array
    {
        return [
            'body' => $this->serializer->serialize($envelope->getMessage(), 'json'),
            'headers' => ['Content-Type' => 'application/json'],
        ];
    }
}
