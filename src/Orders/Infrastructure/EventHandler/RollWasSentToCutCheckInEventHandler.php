<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\EventHandler;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use App\Orders\Domain\Events\RollWasSentToCutCheckInEvent;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: RollWasSentToCutCheckInEvent::class, method: '__invoke')]
/**
 * RollWasSentToPrintCheckInEventHandler handles the RollWasSentToPrintCheckInEvent.
 */
final readonly class RollWasSentToCutCheckInEventHandler implements EventHandlerInterface
{
    /**
     * Constructor for the class.
     *
     * @param PrivateCommandInteractor $privateCommandInteractor Instance of PrivateCommandInteractor
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Invokes the RollWasSentToPrintCheckInEvent event.
     *
     * @param RollWasSentToCutCheckInEvent $event the event object
     */
    public function __invoke(RollWasSentToCutCheckInEvent $event): void
    {
        $this->privateCommandInteractor->unassignEmployeeFromRoll($event->rollId);
    }
}
