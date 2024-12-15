<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Events;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\EventType;

/**
 * RollWasSentToGlowEvent class represents an event that is triggered when a roll is sent to glow.
 *
 * @readonly
 */
final readonly class RollsWereSentToGlowCheckInEvent implements EventInterface
{
    /**
     * Class constructor.
     *
     * @param int[] $rollIds the IDs of the roll
     */
    public function __construct(public array $rollIds)
    {
    }

	public function getEventType(): string
	{
		return EventType::ROLLS_WERE_SENT_TO_GLOW_CHECK_IN;
	}
}
