<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\EventHandler;

use App\Inventory\Application\UseCases\PrivateCommandInteractor;
use App\Inventory\Application\UseCases\PrivateQueryInteractor;
use App\Inventory\Infrastructure\Adapter\Rolls\RollsApiAdapter;
use App\Inventory\Infrastructure\Event\ExternalEvent\RollWasSentToPrintCheckInExternalEvent;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: RollWasSentToPrintCheckInExternalEvent::class, method: '__invoke')]
/**
 * RollWasSentToPrintCheckInEventHandler handles the RollWasSentToPrintCheckInEvent.
 */
final readonly class RollWasSentToPrintingExternalEventHandler implements EventHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param RollsApiAdapter $rollsApiAdapter The RollsApiAdapter instance used for communication with the Rolls API
     */
    public function __construct(private RollsApiAdapter $rollsApiAdapter, private PrivateCommandInteractor $privateCommandInteractor, private PrivateQueryInteractor $privateQueryInteractor)
    {
    }

    /**
     * Invokes the RollWasSentToPrintCheckInEvent event.
     *
     * @param RollWasSentToPrintCheckInExternalEvent $event the event object
     */
    public function __invoke(RollWasSentToPrintCheckInExternalEvent $event): void
    {
        $roll = $this->rollsApiAdapter->getRollById($event->rollId);

        $film = $this->privateQueryInteractor->findAFilm($roll->filmId)->FilmData;

        $this->privateCommandInteractor->useFilm(filmId: $film->id, lengthToUse: $roll->length);
    }
}
