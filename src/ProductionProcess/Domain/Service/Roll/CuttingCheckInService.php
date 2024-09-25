<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Process;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CutCheckInService.
 *
 * This class handles the cutting of a roll.
 */
final readonly class CuttingCheckInService
{
    /**
     * Construct the class.
     *
     * @param RollRepositoryInterface $rollRepository the roll repository
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private GeneralProcessValidation $generalProcessValidator)
    {
    }

    /**
     * Handle the cutting of a roll.
     *
     * @param int $rollId the ID of the roll to be cut
     *
     * @throws NotFoundHttpException if the roll was not found in the repository
     */
    public function handle(int $rollId): void
    {
        $roll = $this->rollRepository->findById($rollId);

        $this->generalProcessValidator->validate($roll);

        $roll->updateProcess(Process::CUTTING_CHECK_IN);

        $this->rollRepository->save($roll);
    }
}
