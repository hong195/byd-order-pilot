<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\EventHandler;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use App\ProductionProcess\Application\UseCase\PrivateQueryInteractor;
use App\ProductionProcess\Domain\Events\RollWasSentToPrintCheckInEvent;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: RollWasSentToPrintCheckInEvent::class, method: '__invoke')]
/**
 * RollWasSentToPrintCheckInEventHandler handles the RollWasSentToPrintCheckInEvent.
 */
final readonly class RollWasSentToPrintCheckInEventHandler implements EventHandlerInterface
{
    /**
     * Constructor for the class.
     *
     * @param PrivateCommandInteractor $privateCommandInteractor Instance of PrivateCommandInteractor
     * @param PrivateQueryInteractor   $privateQueryInteractor   Instance of PrivateQueryInteractor
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor, private PrivateQueryInteractor $privateQueryInteractor)
    {
    }

    /**
     * Invokes the RollWasSentToPrintCheckInEvent event.
     *
     * @param RollWasSentToPrintCheckInEvent $event the event object
     */
    public function __invoke(RollWasSentToPrintCheckInEvent $event): void
    {
        $roll = $this->privateQueryInteractor->findARoll($event->rollId);

        $this->privateCommandInteractor->unassignEmployeeFromRoll($roll->rollData->id);

        $this->privateCommandInteractor->makePrinterUnAvailable($roll->rollData->getPrinter()->id);
    }
}
