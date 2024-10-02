<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Events;

use App\Shared\Domain\Event\EventInterface;

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
     * @param int $rollId the ID of the roll
     */
    public function __construct(public int $rollId)
    {
    }
}
