<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO\PrintedProduct;

use App\ProductionProcess\Application\DTO\OrderData;
use App\ProductionProcess\Application\Service\AssetUrlServiceInterface;
use App\ProductionProcess\Domain\Aggregate\Order;
use App\ProductionProcess\Domain\Aggregate\Product;
use Doctrine\Common\Collections\Collection;

/**
 * OrderData class represents product data.
 */
final readonly class PrintedProductDataTransformer
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
     * @param Product[] $productEntityList an array of Orders entities to convert
     *
     * @return PrintedProductData[] an array of OrderData instances
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
     * @return array<int, Product[]> The converted array format of lamination groups
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
     * @return PrintedProductData the converted PrintedProductData object
     */
    public function fromEntity(Product $product): PrintedProductData
    {
        return new PrintedProductData(
            id: $product->getId(),
			hasPriority: $product->hasPriority(),
			length: $product->getLength(),
			filmType: $product->getFilmType()->value,
			orderNumber: $product->getOrderNumber(),
			addedAt: $product->getDateAdded(),
			laminationType: $product->getLaminationType()?->value,
        );
    }
}
