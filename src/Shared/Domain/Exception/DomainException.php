<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

class DomainException extends \Exception
{
	/**
	 * @throws static
	 */
	public static function because(string $reason, int $code = 0, ?\Throwable $previous = null): self
    {
        throw new static($reason, $code, $previous);
    }
}
