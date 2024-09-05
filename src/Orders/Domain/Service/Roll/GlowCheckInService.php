<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Roll;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Aggregate\Roll;
use App\Orders\Domain\Events\RollsWereSentToGlowCheckInEvent;
use App\Orders\Domain\Exceptions\RollCantBeSentToGlowException;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Orders\Domain\Service\Order\GroupService;
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
     * Constructor for the class.
     *
     * @param RollRepositoryInterface  $rollRepository  the roll repository interface
     * @param GroupService             $groupService    the group service
     * @param RollMaker                $rollMaker       the roll maker
     * @param EventDispatcherInterface $eventDispatcher the event dispatcher interface
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private GroupService $groupService, private RollMaker $rollMaker, private EventDispatcherInterface $eventDispatcher, private GeneralProcessValidation $generalProcessValidatior)
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
        $foundRoll = $this->rollRepository->findById($rollId);

        $this->generalProcessValidatior->validate($foundRoll);

        if (!$foundRoll->getProcess()->equals(Process::PRINTING_CHECK_IN)) {
            throw new RollCantBeSentToGlowException('Roll cannot be glowed! It is not in the correct process.');
        }

        $copyRoll = $foundRoll;
        // copy orders to new collection
        $orders = new ArrayCollection($foundRoll->getOrders()->toArray());

        $this->rollRepository->remove($foundRoll);

        $ordersGroups = $this->groupService->handle($orders);

        $sendToGlowingRolls = [];

        foreach ($ordersGroups as $group => $groupOrders) {
            $roll = $this->rollMaker->make(name: $copyRoll->getName(), filmId: $copyRoll->getFilmId());

            $roll->assignPrinter($foundRoll->getPrinter());

            /** @var Order $firstOrder */
            $firstOrder = $groupOrders->first();
            $hasLamination = null !== $firstOrder->getLaminationType();
            // if roll does not have lamination it goes directly to cut check in, otherwise to glow check in
            $process = $hasLamination ? Process::GLOW_CHECK_IN : Process::CUTTING_CHECK_IN;

            $roll->updateProcess($process);

            foreach ($groupOrders as $order) {
                $roll->addOrder($order);
            }

            $sendToGlowingRolls[] = $roll;
        }

        $this->rollRepository->saveRolls($sendToGlowingRolls);

        $this->eventDispatcher->dispatch(new RollsWereSentToGlowCheckInEvent(array_map(fn (Roll $roll) => $roll->getId(), $sendToGlowingRolls)));
    }
}
