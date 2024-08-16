<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\EventHandler;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use App\Orders\Application\UseCase\PrivateQueryInteractor;
use App\Orders\Domain\Events\RollWasSentToPrintingEvent;
use App\Shared\Application\Event\EventHandlerInterface;

final readonly class RollWasSentToPrintingEventHandler implements EventHandlerInterface
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
     * Invokes the RollWasSentToPrintingEvent event.
     *
     * @param RollWasSentToPrintingEvent $event the event object
     */
    public function __invoke(RollWasSentToPrintingEvent $event): void
    {
        $roll = $this->privateQueryInteractor->findARoll($event->rollId);

        $this->privateCommandInteractor->makePrinterAvailable($roll->rollData->printerId);
    }
}
