<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Events;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\EventType;

/**
 * RollProcessWasUpdatedEvent.
 *
 * Represents an event that is triggered when a roll process is updated.
 */
final readonly class RollProcessWasUpdatedEvent implements EventInterface
{
    /**
     * Class constructor.
     *
     * @param string $rollId the IDs of the roll
     */
    public function __construct(public string $rollId, public string $process)
    {
    }

	public function getEventType(): string
	{
		return EventType::ROLL_PROCESS_WAS_UPDATED;
	}
}
