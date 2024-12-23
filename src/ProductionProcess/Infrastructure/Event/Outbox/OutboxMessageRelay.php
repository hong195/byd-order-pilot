<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Event\Outbox;

use App\ProductionProcess\Infrastructure\Event\DomainEventProducer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\RecoverableMessageHandlingException;

/**
 * Ретранслятор сообщений из Outbox очереди в Брокер сообщений.
 */
#[AsMessageHandler]
final readonly class OutboxMessageRelay
{
    public function __construct(private DomainEventProducer $domainEventProducer, private LoggerInterface $logger)
    {
    }

    public function __invoke(OutboxMessage $outboxMessage): void
    {
        try {
            $this->domainEventProducer->produce($outboxMessage->getMessage());
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
            throw new RecoverableMessageHandlingException($e->getMessage(), 0, $e);
        }
    }
}
