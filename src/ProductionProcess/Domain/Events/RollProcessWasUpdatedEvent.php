<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Events;

use App\Shared\Domain\Event\EventInterface;

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
     * @param int $rollId the IDs of the roll
     */
    public function __construct(public int $rollId)
    {
    }
}
