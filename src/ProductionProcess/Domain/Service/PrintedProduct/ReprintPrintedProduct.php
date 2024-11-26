<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Service\PrintedProduct\Error\ErrorManagementService;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Infrastructure\Security\UserFetcher;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReprintPrintedProduct.
 *
 * This class handles the printing of orders.
 */
final readonly class ReprintPrintedProduct
{
    public function __construct(private PrintedProductRepositoryInterface $printedProductRepository, private ErrorManagementService $errorManagementService, private UserFetcher $userFetcher)
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
    public function handle(int $printedProductId, Process $process, ?string $reason = null): void
    {
        $printedProduct = $this->printedProductRepository->findById($printedProductId);

        if (!$printedProduct) {
            throw new NotFoundHttpException('Printed product not found');
        }

        if (!$printedProduct->getRoll()?->getEmployeeId()) {
            throw new NotFoundHttpException('No employee assigned to the roll');
        }

        $printedProduct->reprint();

        $this->errorManagementService->recordError(
            printedProductId: $printedProduct->getId(),
            process: $process,
            noticerId: $this->userFetcher->requiredUserId(),
            reason: $reason
        );

        $this->printedProductRepository->save($printedProduct);
    }
}
