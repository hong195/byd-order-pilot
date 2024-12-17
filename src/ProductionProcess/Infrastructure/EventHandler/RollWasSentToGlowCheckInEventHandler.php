<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\EventHandler;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use App\ProductionProcess\Application\UseCase\PrivateQueryInteractor;
use App\ProductionProcess\Domain\Events\RollWasSentToGlowCheckInEvent;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: RollWasSentToGlowCheckInEvent::class, method: '__invoke')]
/**
 * RollWasSentToPrintCheckInEventHandler handles the RollWasSentToPrintCheckInEvent.
 */
final readonly class RollWasSentToGlowCheckInEventHandler implements EventHandlerInterface
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
     * @param RollWasSentToGlowCheckInEvent $event the event object
     */
    public function __invoke(RollWasSentToGlowCheckInEvent $event): void
    {
        $roll = $this->privateQueryInteractor->findARoll($event->rollId);

        $printer = $roll->rollData->getPrinter();

        if (!$printer->isAvailable) {
            $this->privateCommandInteractor->makePrinterAvailable($printer->id);
        }
    }
}
