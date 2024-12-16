<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\PrintedProduct\Error\ErrorManagementServiceInterface;
use App\ProductionProcess\Domain\Service\Roll\CheckRemainingProductsService;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Security\UserFetcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReprintPrintedProduct.
 *
 * This class handles the printing of orders.
 */
final readonly class ReprintPrintedProduct
{
    public function __construct(private PrintedProductRepositoryInterface $printedProductRepository, private ErrorManagementServiceInterface $errorManagementService, private UserFetcherInterface $userFetcher, private RollRepositoryInterface $rollRepository, private CheckRemainingProductsService $checkRemainingProductsService)
    {
    }

    /**
     * Handle the printing of a specific printed product.
     *
     * @param string $printedProductId The ID of the printed product to handle
     *
     * @throws NotFoundHttpException If the printed product is not found
     * @throws \Exception
     */
    public function handle(string $printedProductId, Process $process, ?string $reason = null): void
    {
        $printedProduct = $this->printedProductRepository->findById($printedProductId);

        if (!$printedProduct) {
            throw new NotFoundHttpException('Printed product not found');
        }

        $roll = $printedProduct->getRoll();

        if (!$roll) {
            throw new NotFoundHttpException('Printed product is not assigned to a roll');
        }

        if (!$roll->getEmployeeId()) {
            throw new NotFoundHttpException('No employee assigned to the roll');
        }

        $this->errorManagementService->recordError(
            printedProductId: $printedProduct->getId(),
            process: $process,
            noticerId: $this->userFetcher->requiredUserId(),
            reason: $reason
        );

        $roll->reprintProduct($printedProduct);

        $this->rollRepository->save($roll);

        $this->checkRemainingProductsService->check($roll->getId());
    }
}
