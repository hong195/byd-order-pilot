<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CreatePrinters;

use App\Shared\Application\Command\CommandInterface;

/**
 * Class AddRollCommand
 * Implements CommandInterface.
 *
 * Represents a command to add a roll.
 */
readonly class CreatePrintersCommand implements CommandInterface
{
    public function __construct()
    {
    }
}
