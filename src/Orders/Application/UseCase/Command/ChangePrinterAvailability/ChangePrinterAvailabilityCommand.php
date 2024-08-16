<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\ChangePrinterAvailability;

use App\Shared\Application\Command\CommandInterface;

/**
 * Class ChangePrinterAvailabilityCommand
 * Implements CommandInterface.
 *
 * Represents a command to make a printer available.
 */
readonly class ChangePrinterAvailabilityCommand implements CommandInterface
{
    /**
     * Constructs a new Printer object.
     *
     * @param int  $printerId   the ID of the printer
     * @param bool $isAvailable indicates whether the printer is available or not
     */
    public function __construct(public int $printerId, public bool $isAvailable)
    {
    }
}
