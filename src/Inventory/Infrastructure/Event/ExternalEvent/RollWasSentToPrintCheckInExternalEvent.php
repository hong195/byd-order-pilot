<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\Event\ExternalEvent;

use App\Shared\Domain\Event\EventInterface;
use App\Shared\Domain\Event\EventType;

final readonly class RollWasSentToPrintCheckInExternalEvent implements EventInterface
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
        return EventType::ROLL_WAS_SENT_TO_PRINT_CHECK_IN;
    }
}
