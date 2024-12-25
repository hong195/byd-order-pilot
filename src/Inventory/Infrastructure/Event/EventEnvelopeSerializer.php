<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\Event;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Stamp\ErrorDetailsStamp;
use Symfony\Component\Messenger\Stamp\NonSendableStampInterface;
use Symfony\Component\Messenger\Stamp\SerializedMessageStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;

class EventEnvelopeSerializer implements SerializerInterface
{
    private const STAMP_HEADER_PREFIX = 'X-Message-Stamp-';
    private string $format = 'json';

    public function __construct(private SymfonySerializerInterface $serializer)
    {
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        if (empty($encodedEnvelope['body']) || empty($encodedEnvelope['headers'])) {
            throw new MessageDecodingFailedException('Encoded envelope should have at least a "body" and some "headers", or maybe you should implement your own serializer.');
        }

        $stamps = $this->decodeStamps($encodedEnvelope);
        $stamps[] = new SerializedMessageStamp($encodedEnvelope['body']);

        try {
            $message = $this->serializer->deserialize($encodedEnvelope['body'], EventEnvelope::class, $this->format);
        } catch (ExceptionInterface $e) {
            throw new MessageDecodingFailedException('Could not decode message: '.$e->getMessage(), $e->getCode(), $e);
        }

        return new Envelope($message, $stamps);
    }

    public function encode(Envelope $envelope): array
    {
        $envelope = $envelope->withoutStampsOfType(NonSendableStampInterface::class);
        $envelope = $envelope->withoutStampsOfType(ErrorDetailsStamp::class);

        $headers = $this->encodeStamps($envelope) + ['Content-Type' => 'application/json'];

        return [
            'body' => $this->serializer->serialize($envelope->getMessage(), $this->format),
            'headers' => $headers,
        ];
    }

    private function decodeStamps(array $encodedEnvelope): array
    {
        $stamps = [];
        foreach ($encodedEnvelope['headers'] as $name => $value) {
            if (!str_starts_with($name, self::STAMP_HEADER_PREFIX)) {
                continue;
            }

            try {
                $stamps[] = $this->serializer->deserialize($value, substr($name, \strlen(self::STAMP_HEADER_PREFIX)).'[]', $this->format);
            } catch (ExceptionInterface $e) {
                throw new MessageDecodingFailedException('Could not decode stamp: '.$e->getMessage(), $e->getCode(), $e);
            }
        }
        if ($stamps) {
            $stamps = array_merge(...$stamps);
        }

        return $stamps;
    }

    private function encodeStamps(Envelope $envelope): array
    {
        if (!$allStamps = $envelope->all()) {
            return [];
        }

        $headers = [];
        foreach ($allStamps as $class => $stamps) {
            $headers[self::STAMP_HEADER_PREFIX.$class] = $this->serializer->serialize($stamps, $this->format);
        }

        return $headers;
    }
}
