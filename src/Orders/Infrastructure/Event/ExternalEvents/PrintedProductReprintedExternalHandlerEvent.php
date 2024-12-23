<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Event\ExternalEvents;

use App\Orders\Application\ExternalEvents\PrintedProductReprintedExternalEvent;
use App\Orders\Application\UseCase\PrivateCommandInteractor;
use App\Orders\Application\UseCase\PrivateQueryInteractor;
use App\Orders\Infrastructure\Adapter\PrintedProductAdapterInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: PrintedProductReprintedExternalEvent::class, method: '__invoke')]
final readonly class PrintedProductReprintedExternalHandlerEvent
{
    /**
     * Constructor for the Symfony application.
     *
     * @param PrivateCommandInteractor       $privateCommandInteractor interactor for handling private command operations
     * @param PrivateQueryInteractor         $privateQueryInteractor   interactor for handling private query operations
     * @param PrintedProductAdapterInterface $productAdapter           adapter for interacting with printed product data
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor, private PrivateQueryInteractor $privateQueryInteractor, private PrintedProductAdapterInterface $productAdapter)
    {
    }

    /**
     * Handles the PrintedProductReprintedExternalEvent by finding the related product,
     * checking if it is packed, and unpacking it if necessary.
     *
     * @param PrintedProductReprintedExternalEvent $event The PrintedProductReprintedExternalEvent triggering the process
     */
    public function __invoke(PrintedProductReprintedExternalEvent $event): void
    {
        $printedProduct = $this->productAdapter->findByPrintedProductId($event->printedProductId);
        $orderProduct = $this->privateQueryInteractor->findAProduct(productId: $printedProduct->relatedProductId)->productData;

        if ($orderProduct->isPacked) {
            $this->privateCommandInteractor->unpackProduct(productId: $printedProduct->relatedProductId);
        }
    }
}
