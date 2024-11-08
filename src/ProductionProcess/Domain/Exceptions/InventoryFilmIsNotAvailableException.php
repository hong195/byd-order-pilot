<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Exceptions;

use App\Shared\Domain\Exception\DomainException;

final class InventoryFilmIsNotAvailableException extends DomainException
{
	/**
	 * @throws self
	 */
	public static function because(string $reason)
	{
		throw new self($reason);
	}
}
