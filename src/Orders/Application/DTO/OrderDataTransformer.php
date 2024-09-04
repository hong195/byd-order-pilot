<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

use App\Orders\Application\Service\AssetUrlServiceInterface;
use App\Orders\Domain\Aggregate\Order;
use Doctrine\Common\Collections\Collection;

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
     * Converts groups of lamination to an array format.
     *
     * @param array<int, Collection<Order>> $groups The groups of lamination
     *
     * @return array<int, OrderData[]> The converted array format of lamination groups
     */
    public function fromLaminationGroup(array $groups): array
    {
        $result = [];

        foreach ($groups as $group => $items) {
            $result[$group] = $this->fromOrdersEntityList($items->toArray());
        }

        return $result;
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
            customerName: $order->customer->name,
            status: $order->getStatus()->value,
            hasPriority: $order->hasPriority(),
            length: $order->getLength(),
            orderType: $order->getOrderType()->value,
            filmType: $order->getFilmType()->value,
            addedAt: $order->getDateAdded(),
            laminationType: $order->getLaminationType()?->value,
            sortOrder: $order->getSortOrder(),
            orderNumber: $order->getOrderNumber(),
            cutFile: $order->getCutFile() ? $this->assetUrlService->getLink($order->getCutFile()->getPath()) : null,
            printFile: $order->getPrintFile() ? $this->assetUrlService->getLink($order->getPrintFile()->getPath()) : null,
            customerNotes: $order->customer->notes,
            packagingInstructions: $order->getPackagingInstructions(),
            isPacked: $order->isPacked()
        );
    }
}
