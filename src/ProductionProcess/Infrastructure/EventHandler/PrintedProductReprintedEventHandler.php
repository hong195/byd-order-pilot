<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\EventHandler;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use App\ProductionProcess\Application\UseCase\PrivateQueryInteractor;
use App\ProductionProcess\Domain\Events\PrintedProductReprintedEvent;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: PrintedProductReprintedEvent::class, method: '__invoke')]
final readonly class PrintedProductReprintedEventHandler implements EventHandlerInterface
{
    public function __construct(private PrivateQueryInteractor $privateQueryInteractor, private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    public function __invoke(PrintedProductReprintedEvent $event): void
    {
        $printedProduct = $this->privateQueryInteractor->findPrintedProduct(printedProductId: $event->printedProductId)->printedProduct;

		if ($printedProduct->isPacked) {
			$this->privateCommandInteractor->unassignPrintedProduct(id: $event->printedProductId);
		}

        $this->privateCommandInteractor->checkRemainingProducts(rollId: $printedProduct->rollId);
    }
}
