<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\EventHandler;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use App\Orders\Application\UseCase\PrivateQueryInteractor;
use App\Orders\Domain\Events\RollsWereSentToGlowCheckInEvent;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: RollsWereSentToGlowCheckInEvent::class, method: '__invoke')]
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
     * @param RollsWereSentToGlowCheckInEvent $event the event object
     */
    public function __invoke(RollsWereSentToGlowCheckInEvent $event): void
    {
        $rollsIds = $event->rollIds;

        foreach ($rollsIds as $rollId) {
            $this->privateCommandInteractor->unassignEmployeeFromRoll($rollId);
        }

        $roll = $this->privateQueryInteractor->findARoll($rollsIds[0]);

        // rolls sent to glow check in are using the same printer
        $this->privateCommandInteractor->makePrinterAvailable($roll->rollData->printerId);
    }
}
