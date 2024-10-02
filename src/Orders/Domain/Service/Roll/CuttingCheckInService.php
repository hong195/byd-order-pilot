<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Roll;

use App\Orders\Domain\Events\RollWasSentToCutCheckInEvent;
use App\Orders\Domain\Exceptions\RollCantBeSentToCuttingException;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Orders\Domain\ValueObject\Process;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

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
    public function __construct(private RollRepositoryInterface $rollRepository, private GeneralProcessValidation $generalProcessValidator, private EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * Handle the cutting of a roll.
     *
     * @param int $rollId the ID of the roll to be cut
     *
     * @throws NotFoundHttpException            if the roll was not found in the repository
     * @throws RollCantBeSentToCuttingException if the roll cannot be cut due to incorrect process or no orders
     */
    public function handle(int $rollId): void
    {
        $roll = $this->rollRepository->findById($rollId);

        $this->generalProcessValidator->validate($roll);

        $roll->updateProcess(Process::CUTTING_CHECK_IN);

        $this->rollRepository->save($roll);

        $this->eventDispatcher->dispatch(new RollWasSentToCutCheckInEvent($rollId));
    }
}
