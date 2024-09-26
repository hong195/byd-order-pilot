<?php

declare(strict_types=1);

namespace App\Orders\Domain\Event;

use App\Shared\Domain\Event\EventInterface;

final readonly class ProductCreatedEvent implements EventInterface
{
	public function __construct(public int $productId)
	{
	}
}
