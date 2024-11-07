<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Exceptions;

use App\Shared\Domain\Exception\DomainException;

final class RollCantBeSentToCuttingException extends DomainException
{
    /**
     * @throws RollCantBeSentToCuttingException
     */
    public static function because(string $reason): void
    {
        throw new self($reason);
    }
}
