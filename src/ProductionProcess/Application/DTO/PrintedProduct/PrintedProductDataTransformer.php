<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO\PrintedProduct;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use Doctrine\Common\Collections\Collection;

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
     * Converts groups of lamination to an array format.
     *
     * @param array<int, Collection<PrintedProduct>> $groups The groups of lamination
     *
     * @return array<int, PrintedProductData[]> The converted array format of lamination groups
     */
    public function fromLaminationGroup(array $groups): array
    {
        $result = [];

        foreach ($groups as $group => $items) {
            $result[$group] = $this->fromPrintedProductList($items->toArray());
        }

        return $result;
    }

    /**
     * Converts an Orders entity to an OrderData object.
     *
     * @param PrintedProduct $product the Orders entity to convert
     *
     * @return PrintedProductData the converted PrintedProductData object
     */
    public function fromEntity(PrintedProduct $product, ?string $cutFileUrl = null, ?string $printFileUrl = null, bool $isPacked = false): PrintedProductData
    {
        return new PrintedProductData(
            id: $product->getId(),
            relatedProductId: $product->relatedProductId,
            hasPriority: $product->hasPriority(),
            length: $product->getLength(),
            filmType: $product->getFilmType(),
            orderNumber: $product->orderNumber,
            isReprint: $product->isReprint(),
            rollId: $product->getRoll()?->getId(),
            laminationType: $product->getLaminationType(),
            addedAt: $product->getDateAdded(),
            cutFile: $cutFileUrl,
            printFile: $printFileUrl,
			isPacked: $isPacked
        );
    }
}
