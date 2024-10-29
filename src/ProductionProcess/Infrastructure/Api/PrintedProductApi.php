<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Api;

use App\Orders\Infrastructure\Adapter\PrintedProductAdapterInterface;
use App\Orders\Infrastructure\Adapter\PrintedProductProcessAdapterInterface;
use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductData;
use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductProcessData;
use App\ProductionProcess\Application\UseCase\PrivateQueryInteractor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Represents a PrintedProductApi class that implements the PrintedProductProcessAdapterInterface.
 */
final readonly class PrintedProductApi implements PrintedProductProcessAdapterInterface, PrintedProductAdapterInterface
{
    /**
     * Class constructor.
     *
     * @param PrivateQueryInteractor $privateQueryInteractor The private query interactor object
     */
    public function __construct(private PrivateQueryInteractor $privateQueryInteractor)
    {
    }

    /**
     * Gets processes by ids.
     *
     * @param int[] $productsIds The array of process IDs
     *
     * @return Collection<PrintedProductProcessData> The processes associated with the given IDs
     */
    public function getProductsProcessByIds(array $productsIds): Collection
    {
        $result = $this->privateQueryInteractor->getPrintedProductProcessDetail($productsIds);

        return new ArrayCollection($result->items);
    }

	public function findByPrintedProductId(int $productId): ?PrintedProductData
	{
		$printedProductData = $this->privateQueryInteractor->findPrintedProduct($productId)->printedProduct;

		if ($printedProductData === null) {
			return null;
		}

		return $printedProductData;
	}
}
