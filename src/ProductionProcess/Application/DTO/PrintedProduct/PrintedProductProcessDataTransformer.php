<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO\PrintedProduct;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\ValueObject\Process;

/**
 * OrderData class represents order data.
 */
final readonly class PrintedProductProcessDataTransformer
{
    /**
     * Converts a PrintedProduct entity to PrintedProductProcessData object.
     *
     * @param PrintedProduct $printedProduct The PrintedProduct entity to convert
     *
     * @return PrintedProductProcessData The converted PrintedProductProcessData object
     */
    public function fromPrintedProductEntity(PrintedProduct $printedProduct): PrintedProductProcessData
    {
        return new PrintedProductProcessData(
            relatedProductId: $printedProduct->relatedProductId,
            rollId: $printedProduct->getRoll()?->getId(),
            process: $printedProduct->getRoll()?->getProcess()->value ?? Process::ORDER_CHECK_IN->value,
            isFinished: (bool) $printedProduct->getRoll()?->isFinished(),
            isReprint: $printedProduct->isReprint(),
        );
    }

    /**
     * Converts a list of PrintedProduct entities to a list of PrintedProductProcessData objects.
     *
     * @param iterable<PrintedProduct> $printedProducts The iterable containing PrintedProduct entities to convert
     *
     * @return PrintedProductProcessData[] The converted list of PrintedProductProcessData objects
     */
    public function fromPrintedProductList(iterable $printedProducts): array
    {
        $result = [];

        foreach ($printedProducts as $printedProduct) {
            $result[] = $this->fromPrintedProductEntity($printedProduct);
        }

        return $result;
    }
}
