<?php

namespace App\Orders\Application\Service\Product;

use App\Orders\Domain\DTO\ProcessDTO;
use Doctrine\Common\Collections\Collection;

/**
 * Interface CheckProductProcessInterface.
 *
 * Provides methods for processing product data.
 */
interface ProductProcessServiceInterface
{
    /**
     * Process the data based on the given product ID.
     *
     * @param int $id The product ID to process
     *
     * @return ProcessDTO The processed data in a Data Transfer Object
     */
    public function processByProductId(int $id): ProcessDTO;

    /**
     * Processes the given product IDs.
     *
     * @param int[] $ids The array of product IDs to process
     *
     * @return Collection<ProcessDTO> The result of processing the product IDs
     */
    public function processByProductIds(array $ids): Collection;
}
