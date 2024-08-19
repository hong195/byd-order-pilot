<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\EventHandler;

use App\Inventory\Application\UseCases\PrivateCommandInteractor;
use App\Inventory\Application\UseCases\PrivateQueryInteractor;
use App\Inventory\Infrastructure\Adapter\Rolls\RollsApiAdapter;
use App\Orders\Domain\Events\RollWasSentToPrintCheckInEvent;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: RollWasSentToPrintCheckInEvent::class, method: '__invoke')]
/**
 * RollWasSentToPrintCheckInEventHandler handles the RollWasSentToPrintCheckInEvent.
 */
final readonly class RollWasSentToPrintingEventHandler implements EventHandlerInterface
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
     * @param RollWasSentToPrintCheckInEvent $event the event object
     */
    public function __invoke(RollWasSentToPrintCheckInEvent $event): void
    {
        $roll = $this->rollsApiAdapter->getRollById($event->rollId);

        $film = $this->privateQueryInteractor->findAFilm($roll->filmId)->FilmData;

        $this->privateCommandInteractor->updateFilm(id: $film->id, name: $film->name, length: $film->length - $roll->length);
    }
}
