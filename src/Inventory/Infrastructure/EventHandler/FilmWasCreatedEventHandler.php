<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\EventHandler;

use App\Inventory\Application\UseCases\PrivateCommandInteractor;
use App\Inventory\Domain\Events\FilmWasCreatedEvent;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: FilmWasCreatedEvent::class, method: '__invoke')]
/**
 * RollWasSentToPrintCheckInEventHandler handles the RollWasSentToPrintCheckInEvent.
 */
final readonly class FilmWasCreatedEventHandler implements EventHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param PrivateCommandInteractor $privateCommandInteractor the private command interactor
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Invokes the FilmWasCreatedEvent event.
     *
     * @param FilmWasCreatedEvent $event the event object
     */
    public function __invoke(FilmWasCreatedEvent $event): void
    {
        $this->privateCommandInteractor->recordInventoryAdding(filmId: $event->filmId, event: $event->getEventType());
    }
}
