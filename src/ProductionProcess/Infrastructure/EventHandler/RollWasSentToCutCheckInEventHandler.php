<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\EventHandler;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use App\ProductionProcess\Domain\Events\RollWasSentToCutCheckInEvent;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: RollWasSentToCutCheckInEvent::class, method: '__invoke')]
/**
 * RollWasSentToCutCheckInEventHandler handles the RollWasSentToCutCheckInEvent.
 */
final readonly class RollWasSentToCutCheckInEventHandler implements EventHandlerInterface
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
     * Invokable method to handle RollWasSentToCutCheckInEvent.
     *
     * @param RollWasSentToCutCheckInEvent $event The RollWasSentToCutCheckInEvent object
     */
    public function __invoke(RollWasSentToCutCheckInEvent $event): void
    {
        $this->privateCommandInteractor->unassignEmployeeFromRoll($event->rollId);
    }
}
