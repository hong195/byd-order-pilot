<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Exceptions;

final class RollCantBeSentToCuttingException extends \Exception
{
    /**
     * @throws RollCantBeSentToCuttingException
     */
    public static function because(string $reason): void
    {
        throw new self($reason);
    }
}
