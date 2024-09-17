<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Command\RecordInventoryAdding;

use App\Shared\Application\Command\CommandInterface;

final readonly class RecordInventoryAddingCommand implements CommandInterface
{
	public function __construct(public int $filmId, public string $event)
	{
	}
}
