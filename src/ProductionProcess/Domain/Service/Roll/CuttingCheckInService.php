<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Events\RollWasSentToCutCheckInEvent;
use App\ProductionProcess\Domain\Exceptions\RollCantBeSentToCuttingException;
use  App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Domain\Exception\DomainException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class CutCheckInService.
 *
 * This class handles the cutting of a roll.
 */
final readonly class CuttingCheckInService
{
    /**
     * Class constructor.
     *
     * @param RollRepositoryInterface  $rollRepository          the roll repository
     * @param GeneralProcessValidation $generalProcessValidator the general process validator
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private GeneralProcessValidation $generalProcessValidator, private EventDispatcherInterface $eventDispatcher)
    {
    }

	/**
	 * Handle the roll.
	 *
	 * @param string $rollId the ID of the roll
	 *
	 * @throws RollCantBeSentToCuttingException
	 * @throws DomainException
	 */
    public function handle(string $rollId): void
    {
        $roll = $this->rollRepository->findById($rollId);

        $this->generalProcessValidator->validate($roll);

        if (!in_array($roll->getProcess(), [Process::PRINTING_CHECK_IN, Process::GLOW_CHECK_IN])) {
            RollCantBeSentToCuttingException::because('Roll can not be sent to cutting');
        }

        $roll->updateProcess(Process::CUTTING_CHECK_IN);

        $this->rollRepository->save($roll);

        $this->eventDispatcher->dispatch(new RollWasSentToCutCheckInEvent($rollId));
    }
}
