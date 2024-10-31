<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\CheckRemainingProductsService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class UnAssignPrintedProduct
{
    /**
     * Class construction.
     */
    public function __construct(private PrintedProductRepositoryInterface $printedProductRepository, private CheckRemainingProductsService $checkRemainingProductsService)
    {
    }

    /**
     * Handles the printing of a product.
     *
     * @param int $printedProductId the ID of the printed product
     *
     * @throws NotFoundHttpException if the printed product is not found
     */
    public function handle(int $printedProductId): void
    {
        $printedProduct = $this->printedProductRepository->findById($printedProductId);

        if (!$printedProduct) {
            throw new NotFoundHttpException('Printed product not found');
        }

        $printedProduct->unassign();

        $this->printedProductRepository->save($printedProduct);

        $this->checkRemainingProductsService->check($printedProduct->getRoll()->getId());
    }
}
