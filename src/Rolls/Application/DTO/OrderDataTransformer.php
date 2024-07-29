<?php

declare(strict_types=1);

namespace App\Rolls\Application\DTO;

use App\Rolls\Application\Service\AssetUrlServiceInterface;
use App\Rolls\Domain\Aggregate\Order\Order;

/**
 * OrderData class represents order data.
 */
final readonly class OrderDataTransformer
{
    /**
     * Class constructor.
     *
     * @param AssetUrlServiceInterface $assetUrlService the AssetUrlService object
     */
    public function __construct(public AssetUrlServiceInterface $assetUrlService)
    {
    }

    /**
     * Converts an array of Order entities into an array of OrderData instances.
     *
     * @param Order[] $orderEntityList an array of Order entities to convert
     *
     * @return OrderData[] an array of OrderData instances
     */
    public function fromOrdersEntityList(array $orderEntityList): array
    {
        $orderData = [];

        foreach ($orderEntityList as $orderEntity) {
            $orderData[] = $this->fromEntity($orderEntity);
        }

        return $orderData;
    }

    /**
     * Converts an Order entity to an OrderData object.
     *
     * @param Order $order the Order entity to convert
     *
     * @return OrderData the converted OrderData object
     */
    public function fromEntity(Order $order): OrderData
    {
        return new OrderData(
            id: $order->getId(),
            status: $order->getStatus()->value,
            priority: $order->getPriority()->value,
            productType: $order->getProductType()->value,
            rollType: $order->getRollType()->value,
            addedAt: $order->getDateAdded(),
            laminationType: $order->getLaminationType()?->value,
            orderNumber: $order->getOrderNumber(),
            cutFile: $order->getCutFile() ? $this->assetUrlService->getLink($order->getCutFile()->getPath()) : null,
            printFile: $order->getPrintFile() ? $this->assetUrlService->getLink($order->getPrintFile()->getPath()) : null,
        );
    }
}
