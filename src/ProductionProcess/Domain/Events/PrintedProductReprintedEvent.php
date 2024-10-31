<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Events;

use App\Shared\Domain\Event\EventInterface;

final readonly class PrintedProductReprintedEvent implements EventInterface
{
    /**
     * Constructor for the class.
     *
     * @param int $printedProductId the ID of the printed product
     */
    public function __construct(public int $printedProductId)
    {
    }
}
