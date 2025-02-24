<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Service;

use App\Orders\Application\Service\Product\ProductProcessServiceInterface;
use App\Orders\Domain\DTO\ProcessDTO;
use App\Orders\Domain\Service\Order\Product\CheckProductProcessInterface;
use App\Orders\Infrastructure\Adapter\PrintedProductProcessAdapterInterface;
use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductProcessData;
use Doctrine\Common\Collections\Collection;

/**
 * Constructor injection for the PrintedProductProcessAdapterInterface dependency.
 *
 * @param PrintedProductProcessAdapterInterface $printedProductProcessAdapter The adapter to use for retrieving product process data
 */
final readonly class ProductProcessService implements ProductProcessServiceInterface, CheckProductProcessInterface
{
    /**
     * Constructor injection for the PrintedProductProcessAdapterInterface dependency.
     *
     * @param PrintedProductProcessAdapterInterface $printedProductProcessAdapter The adapter to use for retrieving product process data
     */
    public function __construct(private PrintedProductProcessAdapterInterface $printedProductProcessAdapter)
    {
    }

    /**
     * Retrieves process information based on the product ID.
     *
     * @param string $id The product ID to lookup process information for
     *
     * @return ProcessDTO|null The DTO containing the process information
     */
    public function processByProductId(string $id): ?ProcessDTO
    {
        /** @var bool|PrintedProductProcessData $result */
        $result = $this->printedProductProcessAdapter->getProductsProcessByIds([$id])->first();

        if (!$result) {
            return null;
        }

        return new ProcessDTO(
            productId: $result->relatedProductId,
            process: $result->process,
            rollId: $result->rollId,
            isReprint: $result->isReprint,
            isReadyForPacking: $result->isFinished,
            photo: $result->photo ?? null
        );
    }

    /**
     * Retrieves process information based on an array of product IDs.
     *
     * @param int[] $ids An array of product IDs to lookup process information for
     *
     * @return Collection<ProcessDTO> An array of ProcessDTO objects containing the process information for each product ID
     */
    public function processByProductIds(array $ids): Collection
    {
        $result = $this->printedProductProcessAdapter->getProductsProcessByIds($ids);

        return $result->map(
            fn (PrintedProductProcessData $item) => new ProcessDTO(
                productId: $item->relatedProductId,
                process: $item->process,
                rollId: $item->rollId,
                isReprint: $item->isReprint,
                isReadyForPacking: $item->isFinished,
                photo: $item->photo ?? null,
            )
        );
    }

    /**
     * Checks if a product can be packed based on its product ID.
     *
     * @param string $productId The product ID to check packing eligibility for
     *
     * @return bool Returns true if the product is ready for packing, false otherwise
     */
    public function canPack(string $productId): bool
    {
        $process = $this->processByProductId($productId);

        return !$process ? false : $process->isReadyForPacking;
    }
}
