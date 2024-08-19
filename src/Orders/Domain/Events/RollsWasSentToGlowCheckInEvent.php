<?php

declare(strict_types=1);

namespace App\Orders\Domain\Events;

use App\Shared\Domain\Event\EventInterface;

/**
 * RollWasSentToGlowEvent class represents an event that is triggered when a roll is sent to glow.
 *
 * @readonly
 */
final readonly class RollsWasSentToGlowCheckInEvent implements EventInterface
{
    /**
     * Class constructor.
     *
     * @param int[] $rollIds the IDs of the roll
     */
    public function __construct(public array $rollIds)
    {
    }
}
