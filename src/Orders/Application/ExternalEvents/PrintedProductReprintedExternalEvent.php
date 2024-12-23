<?php

declare(strict_types=1);

namespace App\Orders\Application\ExternalEvents;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\EventType;

final readonly class PrintedProductReprintedExternalEvent implements EventInterface
{
    /**
     * Constructor for the class.
     *
     * @param string $printedProductId the ID of the printed product
     */
    public function __construct(public string $printedProductId)
    {
    }

    public function getEventType(): string
    {
        return EventType::PRINTED_PRODUCT_REPRINTED;
    }
}
