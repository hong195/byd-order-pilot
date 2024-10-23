<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order\Extra;

use App\Orders\Domain\Aggregate\Extra;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class SetExtraPackStatusService.
 */
final readonly class SetExtraPackStatusService
{
    /**
     * Constructor method.
     *
     * @param OrderRepositoryInterface $orderRepository the order repository interface object
     */
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }

    /**
     * Handles packing status of an extra for a specific order.
     *
     * @param int  $orderId  The ID of the order
     * @param int  $extraId  The ID of the extra item
     * @param bool $isPacked The new packing status for the extra
     *
     * @throws NotFoundHttpException if order or extra is not found
     */
    public function handle(int $orderId, int $extraId, bool $isPacked): void
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new NotFoundHttpException('Order not found');
        }

        /** @var ?Extra $extra */
        $extra = $order->getExtras()->filter(fn ($extra) => $extra->getId() === $extraId)->first();

        if (!$extra) {
            throw new NotFoundHttpException('Extra not found');
        }

        $extra->setIsPacked($isPacked);

        $this->orderRepository->save($order);
    }
}
