<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\ReprintRoll;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command for reprinting an order.
 */
readonly class ReprintRollCommand implements CommandInterface
{
	/**
	 * Class constructor.
	 *
	 * @param int $rollId The ID of the roll.
	 * @param string $process The process associated with the roll.
	 * @param string|null $reason The reason for the roll, defaults to null.
	 */
    public function __construct(public int $rollId, public string $process, public ?string $reason = null)
    {
    }
}
