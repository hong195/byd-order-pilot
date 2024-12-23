<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\ExternalEvent;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\EventType;

final readonly class ProductAddedExternalEvent implements EventInterface
{
    public function __construct(public string $productId)
    {
    }

    public function getEventType(): string
    {
        return EventType::PRODUCT_CREATED;
    }
}
