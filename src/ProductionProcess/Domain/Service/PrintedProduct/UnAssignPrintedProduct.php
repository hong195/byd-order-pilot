<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Exceptions\UnassignedPrintedProductsException;
use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\CheckRemainingProductsService;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Exception\DomainException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class UnAssignPrintedProduct
{
    /**
     * Class construction.
     */
    public function __construct(private PrintedProductRepositoryInterface $printedProductRepository, private CheckRemainingProductsService $checkRemainingProductsService, private RollRepositoryInterface $rollRepository)
    {
    }

    /**
     * Handles the printing of a product.
     *
     * @param int $printedProductId the ID of the printed product
     *
     * @throws NotFoundHttpException if the printed product is not found
     * @throws DomainException
     */
    public function handle(int $printedProductId): void
    {
        $printedProduct = $this->printedProductRepository->findById($printedProductId);

        if (!$printedProduct) {
            throw new NotFoundHttpException('Printed product not found');
        }

        $roll = $printedProduct->getRoll();

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        if (!$roll->getProcess()->equals(Process::ORDER_CHECK_IN)) {
            UnassignedPrintedProductsException::because('Roll is not in correct process');
        }

        $roll->removePrintedProduct($printedProduct);

        $this->rollRepository->save($roll);

        if ($roll->getPrintedProducts()->isEmpty()) {
            $this->checkRemainingProductsService->check($roll->getId());
        }
    }
}
