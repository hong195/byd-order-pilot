<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\SendToPrintCheckIn;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to change the priority of an order.
 */
readonly class SendRollToPrintCheckIntCommand implements CommandInterface
{
    /**
     * Constructor.
     *
     * @param $rollId $id the ID of the roll to send to print
     */
    public function __construct(public int $rollId)
    {
    }
}
