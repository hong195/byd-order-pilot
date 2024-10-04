<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\PrintedProductCheckInInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReprintPrintedProduct.
 *
 * This class handles the printing of orders.
 */
final readonly class ReprintRoll
{
	/**
	 * Class Constructor.
	 *
	 * @param RollRepositoryInterface $rollRepository The roll repository.
	 * @param PrintedProductRepositoryInterface $printedProductRepository The printed product repository.
	 * @param PrintedProductCheckInInterface $checkIn The printed product check-in instance.
	 */
    public function __construct(private RollRepositoryInterface $rollRepository, private PrintedProductRepositoryInterface $printedProductRepository, private PrintedProductCheckInInterface $checkIn)
    {
    }

	/**
	 * Handle the roll.
	 *
	 * @param int $rollId The ID of the roll.
	 *
	 * @throws NotFoundHttpException If the roll is not found.
	 */
    public function handle(int $rollId): void
    {
        $roll = $this->rollRepository->findById($rollId);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        foreach ($roll->getPrintedProducts() as $printedProduct) {
			$printedProduct->reprint();
            $this->printedProductRepository->save($printedProduct);
        }

        $this->rollRepository->remove($roll);

        $this->checkIn->checkIn();
    }
}
