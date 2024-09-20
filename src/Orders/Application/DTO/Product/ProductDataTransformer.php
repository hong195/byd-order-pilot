<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO\Product;

use App\Orders\Application\DTO\OrderData;
use App\Orders\Application\Service\AssetUrlServiceInterface;
use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Aggregate\Product;
use Doctrine\Common\Collections\Collection;

/**
 * OrderData class represents product data.
 */
final readonly class ProductDataTransformer
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
     * @param Order[] $productEntityList an array of Orders entities to convert
     *
     * @return OrderData[] an array of OrderData instances
     */
    public function fromOrdersEntityList(array $productEntityList): array
    {
        $productData = [];

        foreach ($productEntityList as $productEntity) {
            $productData[] = $this->fromEntity($productEntity);
        }

        return $productData;
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
     * @param Product $product the Orders entity to convert
     *
     * @return ProductData the converted ProductData object
     */
    public function fromEntity(Product $product): ProductData
    {
        return new ProductData(
            id: $product->getId(),
			status: $product->getStatus()->value,
			hasPriority: $product->hasPriority(),
			length: $product->getLength(),
			filmType: $product->getFilmType()->value,
			orderNumber: $product->getOrderNumber(),
			cutFile: $product->getCutFile() ? $this->assetUrlService->getLink($product->getCutFile()->getPath()) : null,
			printFile: $product->getPrintFile() ? $this->assetUrlService->getLink($product->getPrintFile()->getPath()) : null,
			addedAt: $product->getDateAdded(),
			laminationType: $product->getLaminationType()?->value,
        );
    }
}
