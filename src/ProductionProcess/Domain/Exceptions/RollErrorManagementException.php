<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Exceptions;

use App\Shared\Domain\Exception\DomainException;

/**
 * Represents an exception thrown when the roll history is empty.
 */
final class RollErrorManagementException extends DomainException
{
}
