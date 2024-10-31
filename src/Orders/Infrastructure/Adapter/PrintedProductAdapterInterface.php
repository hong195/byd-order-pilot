<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Adapter;

use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductData;

/**
 * Interface PrintedProductProcessAdapterInterface.
 */
interface PrintedProductAdapterInterface
{
    /**
     * Find PrintedProductData entity by printed product ID.
     *
     * @param int $productId The ID of the printed product
     *
     * @return PrintedProductData|null The retrieved PrintedProductData entity, or null if not found
     */
    public function findByPrintedProductId(int $productId): ?PrintedProductData;
}
