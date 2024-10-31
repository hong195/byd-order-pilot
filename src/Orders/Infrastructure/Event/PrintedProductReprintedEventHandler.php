<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Event;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use App\Orders\Application\UseCase\PrivateQueryInteractor;
use App\Orders\Infrastructure\Adapter\PrintedProductAdapterInterface;
use App\ProductionProcess\Domain\Events\PrintedProductReprintedEvent;
use App\Shared\Application\Event\EventHandlerInterface;

final readonly class PrintedProductReprintedEventHandler implements EventHandlerInterface
{
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor, private PrivateQueryInteractor $privateQueryInteractor, private PrintedProductAdapterInterface $productAdapter)
    {
    }

    public function __invoke(PrintedProductReprintedEvent $event): void
    {
        $printedProduct = $this->productAdapter->findByPrintedProductId($event->printedProductId);
		$orderProduct = $this->privateQueryInteractor->findAProduct(productId: $printedProduct->relatedProductId)->productData;

        if ($orderProduct->isPacked) {
            $this->privateCommandInteractor->unpackProduct(productId: $printedProduct->relatedProductId);
        }
    }
}
