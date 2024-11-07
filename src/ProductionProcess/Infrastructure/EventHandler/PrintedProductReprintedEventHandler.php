<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\EventHandler;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use App\ProductionProcess\Domain\Events\PrintedProductReprintedEvent;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 *
 */
#[AsEventListener(event: PrintedProductReprintedEvent::class, method: '__invoke')]
final readonly class PrintedProductReprintedEventHandler implements EventHandlerInterface
{
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

	/**
	 * Method declaration:
	 *
	 * @param PrintedProductReprintedEvent $event the event object containing information about the printed product reprinted
	 * @return void
	 */
	public function __invoke(PrintedProductReprintedEvent $event): void
    {
        $this->privateCommandInteractor->unassignPrintedProduct(id: $event->printedProductId);
    }
}
