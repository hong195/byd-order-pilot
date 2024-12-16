<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\EventHandler;

use App\Inventory\Application\UseCases\Command\RecordInventoryUpdating\RecordInventoryUpdatingCommand;
use App\Inventory\Application\UseCases\PrivateCommandInteractor;
use App\Inventory\Domain\Events\FilmWasDeletedEvent;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: FilmWasDeletedEvent::class, method: '__invoke')]
/**
 * RollWasSentToPrintCheckInEventHandler handles the RollWasSentToPrintCheckInEvent.
 */
final readonly class FilmWasDeletedEventHandler implements EventHandlerInterface
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
     * @param FilmWasDeletedEvent $event the event object
     */
    public function __invoke(FilmWasDeletedEvent $event): void
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
