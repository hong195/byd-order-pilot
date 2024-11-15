<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Exceptions\LockingRollException;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\Shared\Domain\Exception\DomainException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class UnLockRollService
{
    public function __construct(private RollRepositoryInterface $rollRepository)
    {
    }

	/**
	 * @throws LockingRollException
	 * @throws DomainException
	 */
    public function unlock(int $rollId): void
    {
        $roll = $this->rollRepository->findById($rollId);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        if (!$roll->getEmployeeId()) {
            LockingRollException::because('Roll is not assigned to any employee');
        }

        if (!$roll->isLocked) {
            LockingRollException::because('Roll is already unlocked');
        }

        $roll->unlock();

        $this->rollRepository->save($roll);
    }
}
