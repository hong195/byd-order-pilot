<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

class DomainException extends \Exception
{
    public static function because(string $reason, int $code = 0, ?\Throwable $previous = null): self
    {
        return new self($reason, $code, $previous);
    }
}
