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
final readonly class RollWasSentToGlowCheckInEvent implements EventInterface
{
    /**
     * Constructor.
     */
    public function __construct(public string $rollId)
    {
    }

    public function getEventType(): string
    {
        return EventType::ROLL_WAS_SENT_TO_GLOW_CHECK_IN;
    }
}
