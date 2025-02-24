<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\EventHandler;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use App\ProductionProcess\Domain\Events\RollProcessWasUpdatedEvent;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Handles the RollProcessWasUpdated event.
 *
 * @readonly
 */
#[AsEventListener(event: RollProcessWasUpdatedEvent::class, method: '__invoke')]
final readonly class RollProcessWasUpdatedEventHandler implements EventHandlerInterface
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
     * Invokable method for the class.
     *
     * @param RollProcessWasUpdatedEvent $event The event object
     */
    public function __invoke(RollProcessWasUpdatedEvent $event): void
    {
        $this->privateCommandInteractor->recoredRollProcessUpdate(rollId: $event->rollId, process: $event->process);
    }
}
