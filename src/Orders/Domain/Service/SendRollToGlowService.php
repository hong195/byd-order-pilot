<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service;

use App\Orders\Domain\Exceptions\RollCantBeSentToGlowException;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Orders\Domain\ValueObject\Process;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * This class is responsible for handling the printing process.
 */
final readonly class SendRollToGlowService
{
    /**
     * Create a new instance of the class.
     *
     * @param RollRepositoryInterface $rollRepository the roll repository
     */
    public function __construct(private RollRepositoryInterface $rollRepository)
    {
    }

    /**
     * Print a roll.
     *
     * @param int $rollId The ID of the roll to be printed
     *
     * @throws NotFoundHttpException         If the roll is not found
     * @throws RollCantBeSentToGlowException
     */
    public function handle(int $rollId): void
    {
        $roll = $this->rollRepository->findById($rollId);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        if (!$roll->getProcess()->equals(Process::PRINTING_CHECK_IN)) {
            throw new RollCantBeSentToGlowException('Roll cannot be glowed! It is not in the correct process.');
        }

        if ($roll->getOrders()->isEmpty()) {
            throw new RollCantBeSentToGlowException('Roll cannot be glowed! It has no orders.');
        }

        $roll->updateProcess(Process::GLOW_CHECK_IN);

        $this->rollRepository->save($roll);
    }
}
