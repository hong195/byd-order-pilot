<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Events;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\EventType;

/**
 * RollWasSentToPrintCheckInEvent class represents an event that is triggered when a roll is sent to printing.
 *
 * @readonly
 */
final readonly class RollWasSentToCutCheckInEvent implements EventInterface
{
    /**
     * Class constructor.
     *
     * @param string $rollId the ID of the roll
     */
    public function __construct(public string $rollId)
    {
    }

	public function getEventType(): string
	{
		return EventType::ROLL_WAS_SENT_TO_CUT_CHECK_IN;
	}
}
