<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Exceptions\LockingRollException;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;

final readonly class UnLockRollService
{
    public function __construct(private RollRepositoryInterface $rollRepository)
    {
    }

    /**
     * @throws LockingRollException
     */
    public function unlock(int $rollId): void
    {
        $roll = $this->rollRepository->findById($rollId);

        if (!$roll->isLocked) {
            LockingRollException::because('Roll is already unlocked');
        }

        $roll->unlock();

        $this->rollRepository->save($roll);
    }
}
