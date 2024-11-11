<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Exceptions\LockingRollException;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;

final readonly class LockRollService
{
    public function __construct(private RollRepositoryInterface $rollRepository)
    {
    }

    /**
     * @throws LockingRollException
     */
    public function lock(int $rollId): void
    {
        $roll = $this->rollRepository->findById($rollId);

        if ($roll->isLocked) {
            LockingRollException::because('Roll is already locked');
        }

        $roll->lock();

        $this->rollRepository->save($roll);
    }
}
