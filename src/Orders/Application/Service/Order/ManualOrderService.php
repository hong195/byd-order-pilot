<?php

declare(strict_types=1);

namespace App\Orders\Application\Service\Order;

use App\Orders\Application\DTO\Order\ManualCreateOrderDTO;
use App\Orders\Domain\Aggregate\Customer;
use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Factory\OrderFactory;
use App\Orders\Infrastructure\Repository\OrderRepository;
use App\Shared\Infrastructure\Repository\MediaFileRepository;

/**
 * Constructs a new instance of the class.
 *
 * @param OrderFactory        $orderFactory        the order factory instance
 * @param OrderRepository     $orderRepository     the order repository instance
 * @param MediaFileRepository $mediaFileRepository the media file repository instance
 */
final readonly class ManualOrderService
{
    private const ORDER_MANUAL_PREFIX = 'MANUAL';

    /**
     * Constructs a new instance of the class.
     *
     * @param OrderFactory    $orderFactory    the order factory instance
     * @param OrderRepository $orderRepository the order repository instance
     */
    public function __construct(private OrderFactory $orderFactory, private OrderRepository $orderRepository)
    {
    }

    /**
     * Creates and saves a new order.
     *
     * @return Order the created order
     */
    public function create(ManualCreateOrderDTO $orderData): Order
    {
        $this->orderFactory->withPackagingInstructions($orderData->packagingInstructions);

        $order = $this->orderFactory->make(
            customer: new Customer(name: $orderData->customerName, notes: $orderData->customerNotes), shippingAddress: $orderData->shippingAddress
        );

        $this->orderRepository->save($order);

        $order->changeOrderNumber(self::ORDER_MANUAL_PREFIX.'-'.$order->getId());

        $this->orderRepository->save($order);

        return $order;
    }
}
