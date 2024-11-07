<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO\Product;

use App\Orders\Domain\Aggregate\Product;
use App\Shared\Application\Service\AssetUrlServiceInterface;

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
     * @param Product[] $productEntityList an array of Orders entities to convert
     *
     * @return ProductData[] an array of OrderData instances
     */
    public function fromProductsEntityList(array $productEntityList): array
    {
        $productData = [];

        foreach ($productEntityList as $productEntity) {
            $productData[] = $this->fromEntity($productEntity);
        }

        return $productData;
    }

    /**
     * Converts an Orders entity to an OrderData object.
     *
     * @param Product $product the Orders entity to convert
     *
     * @return ProductData the converted PrintedProductData object
     */
    public function fromEntity(Product $product): ProductData
    {
        return new ProductData(
            id: $product->getId(),
			length: round($product->getLength(), 2),
			filmType: $product->getFilmType(),
			orderNumber: $product->getOrderNumber(),
			cutFile: $product->getCutFile() ? $this->assetUrlService->getLink($product->getCutFile()->getPath()) : null,
			printFile: $product->getPrintFile() ? $this->assetUrlService->getLink($product->getPrintFile()->getPath()) : null,
			isPacked: $product->isPacked(),
			addedAt: $product->getDateAdded(),
			orderId: $product->getOrder()?->getId(),
			laminationType: $product->getLaminationType(),
        );
    }
}
