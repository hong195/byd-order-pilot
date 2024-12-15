<?php

declare(strict_types=1);

namespace App\Orders\Domain\Event;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\EventType;

final readonly class ProductCreatedEvent implements EventInterface
{
    public function __construct(public int $productId)
    {
    }

    public function getEventType(): string
    {
        return EventType::PRODUCT_CREATED;
    }
}
