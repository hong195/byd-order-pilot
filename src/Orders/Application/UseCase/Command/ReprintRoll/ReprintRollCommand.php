<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\ReprintRoll;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command for reprinting an order.
 */
readonly class ReprintRollCommand implements CommandInterface
{
    /**
     * Constructor.
     *
     * @param int $rollId the ID of the roll
     */
    public function __construct(public int $rollId)
    {
    }
}
