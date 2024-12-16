<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\EventHandler;

use App\Inventory\Application\UseCases\Command\RecordInventoryUpdating\RecordInventoryUpdatingCommand;
use App\Inventory\Application\UseCases\PrivateCommandInteractor;
use App\Inventory\Domain\Events\FilmWasUpdatedEvent;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * FilmWasUpdatedEventHandler handles the FilmWasUpdatedEvent.
 *
 * @since 1.0.0 // enter the initial version here
 */
#[AsEventListener(event: FilmWasUpdatedEvent::class, method: '__invoke')]
final readonly class FilmWasUpdatedEventHandler implements EventHandlerInterface
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
     * Invokes the FilmLengthWasUpdatedEvent event.
     *
     * @param FilmWasUpdatedEvent $event the event object
     */
    public function __invoke(FilmWasUpdatedEvent $event): void
    {
        $command = new RecordInventoryUpdatingCommand(
            filmId: $event->filmId,
            event: $event->getEventType(),
            newSize: $event->newSize,
            oldSize: $event->oldSize,
			filmType: $event->filmType
        );
        $this->privateCommandInteractor->recordInventoryUpdating($command);
    }
}
