<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Command\RecordInventoryUpdating;

use App\Shared\Application\Command\CommandInterface;

final readonly class RecordInventoryUpdatingCommand implements CommandInterface
{
	public function __construct(public string $filmId, public string $event, public float $newSize, public float $oldSize, public string $filmType)
	{
	}
}
