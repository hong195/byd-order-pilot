<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order\Extra;

use App\Orders\Domain\Aggregate\Extra;
use App\Orders\Domain\Exceptions\CantPackExtraException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\ValueObject\Status;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class SetPackExtra
{
	public function __construct(private OrderRepositoryInterface $orderRepository)
	{
	}

	/**
	 * @throws CantPackExtraException
	 */
	public function handle(int $orderId, int $extraId, bool $isPacked): void
	{
		$order = $this->orderRepository->findById($orderId);

		if (!$order) {
			throw new NotFoundHttpException('Order not found');
		}

		if (!$order->getStatus()->equals(Status::SHIP_AND_COLLECT)) {
			throw new CantPackExtraException('Order has invalid status');
		}
		/** @var Extra $extra */
		$extra = $order->getExtras()->filter(fn($extra) => $extra->getId() === $extraId)->first();

		if (!$extra) {
			throw new NotFoundHttpException('Extra not found');
		}

		$extra->setIsPacked($isPacked);
	}
}
