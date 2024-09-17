<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\EventHandler;

use App\Inventory\Application\UseCases\Command\RecordInventoryUpdating\RecordInventoryUpdatingCommand;
use App\Inventory\Application\UseCases\PrivateCommandInteractor;
use App\Inventory\Domain\Events\FilmLengthWasUpdatedEvent;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: FilmLengthWasUpdatedEvent::class, method: '__invoke')]
final readonly class FilmLengthWasUpdatedEventHandler implements EventHandlerInterface
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
     * @param FilmLengthWasUpdatedEvent $event the event object
     */
    public function __invoke(FilmLengthWasUpdatedEvent $event): void
    {
		$command = new RecordInventoryUpdatingCommand(
			filmId: $event->filmId,
			event: (string) $event,
			newSize: $event->newSize,
			oldSize: $event->oldSize
		);
		$this->privateCommandInteractor->recordInventoryUpdating($command);
    }
}
