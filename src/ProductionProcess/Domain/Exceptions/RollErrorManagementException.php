<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Exceptions;

/**
 * Represents an exception thrown when the roll history is empty.
 */
final class RollErrorManagementException extends \Exception
{
    /**
     * Creates a new instance of EmptyRollHistoryException with the given reason.
     *
     * @param string $reason The reason for the exception
     *
     * @throws RollErrorManagementException
     */
    public static function because(string $reason): void
    {
        throw new self($reason);
    }
}
