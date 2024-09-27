<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Status;
use App\Shared\Domain\Service\AssertService;

final readonly class ChangeStatus
{
    public function __construct(private PrintedProductRepositoryInterface $printedProductRepository)
    {
    }

    /**
     * Handle the printed product based on the provided parameters.
     *
     * @param int    $printedProductId the ID of the printed product
     * @param Status $status           the status to be assigned to the printed product
     */
    public function handle(int $printedProductId, Status $status): void
    {
        $printedProduct = $this->printedProductRepository->findById($printedProductId);

        AssertService::notNull($printedProduct, 'Order not found.');

        $printedProduct->changeStatus($status);

        if ($status->equals(Status::UNASSIGNED)) {
            $printedProduct->removeRoll();
        }

        $this->printedProductRepository->save($printedProduct);
    }
}
