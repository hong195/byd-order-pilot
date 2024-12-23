<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Event\Outbox;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Service\UlidService;

final class OutboxMessage
{
    private string $id;
    private EventInterface $message;

    public function __construct(EventInterface $message)
    {
        $this->id = UlidService::generate();
        $this->message = $message;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMessage(): EventInterface
    {
        return $this->message;
    }
}
