<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

use App\Orders\Application\Service\AssetUrlServiceInterface;
use App\Orders\Domain\Aggregate\Order;

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
     * Converts an array of Orders entities into an array of OrderData instances.
     *
     * @param Order[] $orderEntityList an array of Orders entities to convert
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
     * Converts an Orders entity to an OrderData object.
     *
     * @param Order $order the Orders entity to convert
     *
     * @return OrderData the converted OrderData object
     */
    public function fromEntity(Order $order): OrderData
    {
        return new OrderData(
            id: $order->getId(),
            status: $order->getStatus()->value,
            hasPriority: $order->hasPriority(),
            length: $order->getLength(),
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
