<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Factory;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;

/**
 * Class HistoryFactory.
 *
 * This class is responsible for creating a new History object from a Roll object.
 */
final class PrintedProductFactory
{
    private PrintedProduct $printedProduct;

    /**
     * Creates a new instance of PrintedProduct based on the provided data.
     *
     * @param int    $relatedProductId The related product ID
     * @param string $orderNumber      The order number
     * @param string $filmType         The film type
     * @param float  $length           The length of the product
     *
     * @return PrintedProduct A new PrintedProduct instance
     */
    public function make(int $relatedProductId, string $orderNumber, string $filmType, float $length): PrintedProduct
    {
        return new PrintedProduct(
            relatedProductId: $relatedProductId,
            orderNumber: $orderNumber,
            filmType: $filmType,
            length: $length
        );
    }
}
