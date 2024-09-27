<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\ValueObject\Status;
use App\ProductionProcess\Infrastructure\Repository\PrintedProductRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class UnAssignPrintedProduct
{
    /**
     * Class construction.
     */
    public function __construct(private PrintedProductRepository $printedProductRepository)
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

        $printedProduct->changeStatus(Status::UNASSIGNED);
        $printedProduct->changeSortOrder(null);
        $printedProduct->removeRoll();

        $this->printedProductRepository->save($printedProduct);
    }
}
