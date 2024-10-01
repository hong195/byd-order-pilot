<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO\PrintedProduct;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;

/**
 * OrderData class represents product data.
 */
final readonly class PrintedProductDataTransformer
{
    /**
     * Converts an array of Orders entities into an array of OrderData instances.
     *
     * @param PrintedProduct[] $printedProducts an array of Orders entities to convert
     *
     * @return PrintedProductData[] an array of OrderData instances
     */
    public function fromPrintedProductList(array $printedProducts): array
    {
        $productData = [];

        foreach ($printedProducts as $productEntity) {
            $productData[] = $this->fromEntity($productEntity);
        }

        return $productData;
    }

    /**
     * Converts an Orders entity to an OrderData object.
     *
     * @param PrintedProduct $product the Orders entity to convert
     *
     * @return PrintedProductData the converted PrintedProductData object
     */
    public function fromEntity(PrintedProduct $product): PrintedProductData
    {
        return new PrintedProductData(
            id: $product->getId(),
			hasPriority: $product->hasPriority(),
			length: $product->getLength(),
			filmType: $product->getFilmType(),
			orderNumber: $product->orderNumber,
			rollId: $product->getRoll()?->getId(),
			laminationType: $product->getLaminationType(),
			addedAt: $product->getDateAdded(),
        );
    }
}
