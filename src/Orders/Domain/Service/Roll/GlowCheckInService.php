<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Roll;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Aggregate\Roll\Roll;
use App\Orders\Domain\Events\RollsWereSentToGlowCheckInEvent;
use App\Orders\Domain\Exceptions\RollCantBeSentToGlowException;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Orders\Domain\Service\Order\GroupService;
use App\Orders\Domain\Service\Roll\History\HistorySyncService;
use App\Orders\Domain\ValueObject\Process;
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
	 *
	 * @param RollRepositoryInterface $rollRepository
	 * @param GroupService $groupService
	 * @param EventDispatcherInterface $eventDispatcher
	 * @param GeneralProcessValidation $generalProcessValidatior
	 */
    public function __construct(
        private RollRepositoryInterface $rollRepository, private GroupService $groupService,
        private RollMaker $rollMaker, private EventDispatcherInterface $eventDispatcher, private GeneralProcessValidation $generalProcessValidatior,
        private HistorySyncService $historySyncService,
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

		$orders = new ArrayCollection($rollToGlow->getOrders()->toArray());
		$ordersGroups = $this->groupService->handle($orders);

		if (count($ordersGroups) === 1) {
			$firstOrder = $rollToGlow->getOrders()->first();
			$hasLamination = null !== $firstOrder->getLaminationType();
			$process = $hasLamination ? Process::GLOW_CHECK_IN : Process::CUTTING_CHECK_IN;

			$rollToGlow->updateProcess($process);

			$this->rollRepository->save($rollToGlow);
			$this->eventDispatcher->dispatch(new RollsWereSentToGlowCheckInEvent([$rollToGlow->getId()]));

			return;
		}

		$rollToGlow->removeProducts();
        $sendToGlowingRolls = [];

		$rollToGlow->updateProcess(Process::CUT);

		$this->rollRepository->save($rollToGlow);

		foreach ($ordersGroups as $group => $groupOrders) {
			$roll = $this->rollMaker->make(name: $rollToGlow->getName(), filmId: $rollToGlow->getFilmId());

			$roll->setEmployeeId($rollToGlow->getEmployeeId());
			$roll->assignPrinter($rollToGlow->getPrinter());
			$roll->setParentRoll($rollToGlow);

            /** @var Order $firstOrder */
            $firstOrder = $groupOrders->first();
            $hasLamination = null !== $firstOrder->getLaminationType();
            // if roll does not have lamination it goes directly to cut check in, otherwise to glow check in
            $process = $hasLamination ? Process::GLOW_CHECK_IN : Process::CUTTING_CHECK_IN;

            $roll->updateProcess($process);

            foreach ($groupOrders as $order) {
                $roll->addOrder($order);
            }

			$this->rollRepository->save($roll);
			$sendToGlowingRolls[] = $roll;
        }

        $this->eventDispatcher->dispatch(new RollsWereSentToGlowCheckInEvent(array_map(fn (Roll $roll) => $roll->getId(), $sendToGlowingRolls)));
    }
}
