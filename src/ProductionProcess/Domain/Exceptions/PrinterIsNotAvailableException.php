<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Exceptions;

use App\Shared\Domain\Exception\DomainException;

final class PrinterIsNotAvailableException extends DomainException
{
}
