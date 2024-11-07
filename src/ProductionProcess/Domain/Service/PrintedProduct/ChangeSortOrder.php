<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ChangeSortOrder.
 *
 * Handles the sorting of orders for a specific roll.
 */
final readonly class ChangeSortOrder
{
    /**
     * @return void
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private PrintedProductRepositoryInterface $printedProductRepository, private GroupService $groupService)
    {
    }

    /**
     * Handle the roll update.
     *
     * @param int   $rollId     The ID of the roll
     * @param int   $group      The group number the orders by lamination
     * @param int[] $sortOrders The sort order to apply to the orders
     *
     * @throws NotFoundHttpException If the roll with the specified ID is not found
     */
    public function handle(int $rollId, int $group, array $sortOrders): void
    {
        $roll = $this->rollRepository->findById($rollId);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        $printedProductsGroupedByLamination = $this->groupService->handle($roll->getPrintedProducts());

        if (!isset($printedProductsGroupedByLamination[$group])) {
            throw new NotFoundHttpException('ProductGroup not found');
        }

        $groupOrders = $printedProductsGroupedByLamination[$group];

        foreach ($sortOrders as $printedProductId => $sortOrder) {
            /** @var PrintedProduct|false $printedProduct */
            $printedProduct = $groupOrders->filter(fn (PrintedProduct $printedProduct) => $printedProduct->getId() === $printedProductId)->first();

            if (!$printedProduct) {
                throw new NotFoundHttpException('Printed product not found');
            }

            $printedProduct->changeSortOrder($sortOrder);
            $this->printedProductRepository->save($printedProduct);
        }
    }
}
