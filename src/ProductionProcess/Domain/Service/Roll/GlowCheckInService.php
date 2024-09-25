<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Aggregate\Job;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Events\RollsWereSentToGlowCheckInEvent;
use App\ProductionProcess\Domain\Exceptions\RollCantBeSentToGlowException;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Job\GroupService;
use App\ProductionProcess\Domain\ValueObject\Process;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * This class is responsible for handling the printing process.
 */
final readonly class GlowCheckInService
{
    /**
     * Class constructor.
     */
    public function __construct(
        private RollRepositoryInterface $rollRepository, private GroupService $groupService,
        private RollMaker $rollMaker, private EventDispatcherInterface $eventDispatcher,
        private GeneralProcessValidation $generalProcessValidatior,
    ) {
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
        $rollToGlow = $this->rollRepository->findById($rollId);

        $this->generalProcessValidatior->validate($rollToGlow);

        if (!$rollToGlow->getProcess()->equals(Process::PRINTING_CHECK_IN)) {
            throw new RollCantBeSentToGlowException('Roll cannot be glowed! It is not in the correct process.');
        }

        $jobs = new ArrayCollection($rollToGlow->getJobs()->toArray());
        $jobsGroups = $this->groupService->handle($jobs);

        if (1 === count($jobsGroups)) {
            $firstJob = $rollToGlow->getJobs()->first();
            $hasLamination = null !== $firstJob->getLaminationType();
            $process = $hasLamination ? Process::GLOW_CHECK_IN : Process::CUTTING_CHECK_IN;

            $rollToGlow->updateProcess($process);

            $this->rollRepository->save($rollToGlow);
            $this->eventDispatcher->dispatch(new RollsWereSentToGlowCheckInEvent([$rollToGlow->getId()]));

            return;
        }

        $rollToGlow->removeJobs();
        $sendToGlowingRolls = [];

        $rollToGlow->updateProcess(Process::CUT);

        $this->rollRepository->save($rollToGlow);

        foreach ($jobsGroups as $group => $groupJobs) {
            $roll = $this->rollMaker->make(name: $rollToGlow->getName(), filmId: $rollToGlow->getFilmId());

            $roll->setEmployeeId($rollToGlow->getEmployeeId());
            $roll->assignPrinter($rollToGlow->getPrinter());
            $roll->setParentRoll($rollToGlow);

            /** @var Job $firstJob */
            $firstJob = $groupJobs->first();
            $hasLamination = null !== $firstJob->getLaminationType();
            // if roll does not have lamination it goes directly to cut check in, otherwise to glow check in
            $process = $hasLamination ? Process::GLOW_CHECK_IN : Process::CUTTING_CHECK_IN;

            $roll->updateProcess($process);

            foreach ($groupJobs as $job) {
                $roll->addJob($job);
            }

            $this->rollRepository->save($roll);
            $sendToGlowingRolls[] = $roll;
        }

        $this->eventDispatcher->dispatch(new RollsWereSentToGlowCheckInEvent(array_map(fn (Roll $roll) => $roll->getId(), $sendToGlowingRolls)));
    }
}
