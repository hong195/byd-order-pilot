<?php

declare(strict_types=1);

namespace App\Orders\Domain\Event;

use App\Shared\Domain\Event\EventInterface;

final readonly class ProductPackedEvent implements EventInterface
{
    /**
     * Constructor for the class.
     *
     * @param int $productId the unique identifier for the product
     */
    public function __construct(public readonly int $productId)
    {
    }
}
