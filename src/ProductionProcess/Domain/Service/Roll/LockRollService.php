<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Exceptions\LockingRollException;
use  App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Exception\DomainException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class LockRollService
{
    public function __construct(private RollRepositoryInterface $rollRepository)
    {
    }

    /**
     * @throws LockingRollException
	 * @throws DomainException
	 */
    public function lock(int $rollId): void
    {
        $roll = $this->rollRepository->findById($rollId);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

		if (!$roll->getProcess()->equals(Process::ORDER_CHECK_IN)) {
			LockingRollException::because("Roll has incorrect process");
		}

		if (!$roll->getEmployeeId()) {
			LockingRollException::because('Roll is not assigned to any employee');
		}

        if ($roll->isLocked) {
            LockingRollException::because('Roll is already locked');
        }

        $roll->lock();

        $this->rollRepository->save($roll);
    }
}
