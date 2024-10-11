<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Adapter;

use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductProcessData;
use Doctrine\Common\Collections\Collection;

/**
 * Interface PrintedProductProcessAdapterInterface.
 */
interface PrintedProductProcessAdapterInterface
{
    /**
     * Retrieves products details by their IDs.
     *
     * @param int[] $productsIds the IDs of the products to retrieve details for
     *
     * @return Collection<PrintedProductProcessData> the details of the products identified by the provided IDs
     */
    public function getProductsProcessByIds(array $productsIds): Collection;
}
