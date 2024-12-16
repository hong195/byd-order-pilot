<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\EventHandler;

use App\Orders\Application\DTO\Product\ProductData;
use App\Orders\Domain\Event\ProductAddedEvent;
use App\ProductionProcess\Application\UseCase\Command\CreatePrintedProduct\CreatePrintedProductCommand;
use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use App\ProductionProcess\Infrastructure\Adapter\Order\OrderApiInterface;
use App\Shared\Application\Event\EventHandlerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Handles the RollProcessWasUpdated event.
 *
 * @readonly
 */
#[AsEventListener(event: ProductAddedEvent::class, method: '__invoke')]
final readonly class ProductAddedEventHandler implements EventHandlerInterface
{
    /**
     * Constructor for the class.
     *
     * @param PrivateCommandInteractor $privateCommandInteractor Instance of PrivateCommandInteractor
     */
    public function __construct(private OrderApiInterface $ordersAdapter, private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

	/**
	 * Handle the product added event
	 *
	 * @param ProductAddedEvent $event The event object
	 */
    public function __invoke(ProductAddedEvent $event): void
    {
		/** @var ProductData $product */

		$product = $this->ordersAdapter->findProductById($event->productId);

		$command = new CreatePrintedProductCommand(
			productId: $product->id,
			orderNumber: $product->orderNumber,
			length: $product->length,
			filmType: $product->filmType,
			laminationType: $product->laminationType,
		);

        $this->privateCommandInteractor->createPrintedProduct($command);
    }
}
