<?php

namespace App\Orders\Domain\Service\Order\Product;

/**
 * Interface CheckProductProcessInterface.
 *
 * Provides methods for processing product data.
 */
interface CheckProductProcessInterface
{
    /**
     * Determine if the given product can be packed.
     *
     * @param int $productId The ID of the product to check
     *
     * @return bool True if the product can be packed, false otherwise
     */
    public function canPack(int $productId): bool;
}
