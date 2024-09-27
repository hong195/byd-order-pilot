<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\PrintedProductsCheckInService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReprintOrder.
 *
 * This class handles the printing of orders.
 */
final readonly class ReprintPrintedProduct
{
    /**
     * Class constructor.
     *
     * @param PrintedProductRepositoryInterface $printedProductRepository the printed product repository
     * @param PrintedProductsCheckInService     $checkInService           the check-in service for printed products
     */
    public function __construct(private PrintedProductRepositoryInterface $printedProductRepository, private PrintedProductsCheckInService $checkInService)
    {
    }

	/**
	 * Handle the printing of a specific printed product.
	 *
	 * @param int $printedProductId The ID of the printed product to handle
	 *
	 * @throws NotFoundHttpException If the printed product is not found
	 * @throws \Exception
	 */
    public function handle(int $printedProductId): void
    {
        $printedProduct = $this->printedProductRepository->findById($printedProductId);

        if (!$printedProduct) {
            throw new NotFoundHttpException('Printed product not found');
        }

        $printedProduct->reprint();

        $this->printedProductRepository->save($printedProduct);

        $this->checkInService->checkIn();
    }
}
