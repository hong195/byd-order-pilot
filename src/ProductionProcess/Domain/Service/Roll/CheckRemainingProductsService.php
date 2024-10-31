<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Repository\PrinterRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class CheckRemainingProductsService
{
    /**
     * Constructor method for the class.
     *
     * @param RollRepositoryInterface $rollRepository The roll repository interface instance
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private PrinterRepositoryInterface $printerRepository)
    {
    }

    /**
     * Check roll if there are printed products left, if not roll will be removed.
     *
     * @param int $rollId The ID of the roll to check
     *
     * @throws NotFoundHttpException If the roll is not found
     */
    public function check(int $rollId): void
    {
        $roll = $this->rollRepository->findById($rollId);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        if (!$roll->getPrintedProducts()->isEmpty()) {
            return;
        }

		$this->rollRepository->remove($roll);

		if ($printer = $roll->getPrinter()) {
			$printer->changeAvailability(true);
			$this->printerRepository->save($printer);
		}
    }
}
