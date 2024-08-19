<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service;

use App\Orders\Domain\Aggregate\Order;
use App\Orders\Domain\Aggregate\Roll;
use App\Orders\Domain\Events\RollsWereSentToGlowCheckInEvent;
use App\Orders\Domain\Exceptions\RollCantBeSentToGlowException;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Orders\Domain\Service\Order\GroupService;
use App\Orders\Domain\ValueObject\Process;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * This class is responsible for handling the printing process.
 */
final readonly class SendRollToGlowCheckInService
{
    /**
     * Constructor for the class.
     *
     * @param RollRepositoryInterface  $rollRepository  the roll repository interface
     * @param GroupService             $groupService    the group service
     * @param RollMaker                $rollMaker       the roll maker
     * @param EventDispatcherInterface $eventDispatcher the event dispatcher interface
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private GroupService $groupService, private RollMaker $rollMaker, private EventDispatcherInterface $eventDispatcher)
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

        // Group orders by the lamination
        $ordersGroups = $this->groupService->handle($roll->getOrders());

        if ($roll->getOrders()->isEmpty()) {
            throw new RollCantBeSentToGlowException('Roll cannot be glowed! It has no orders.');
        }

        $sendToGlowingRolls = [];

        foreach ($ordersGroups as $group => $orders) {
            $roll = $this->rollMaker->make(name: $roll->getName(), filmId: $roll->getFilmId());

            $roll->assignPrinter($roll->getPrinter());

            /** @var Order $firstOrder */
            $firstOrder = $orders->first();
            $hasLamination = null !== $firstOrder->getLaminationType();
            // if roll does not have lamination it goes directly to cut check in, otherwise to glow check in
            $process = $hasLamination ? Process::GLOW_CHECK_IN : Process::CUTTING_CHECK_IN;

            $roll->updateProcess($process);

            foreach ($orders as $order) {
                $roll->addOrder($order);
            }

            $sendToGlowingRolls[] = $roll;
        }

        $this->rollRepository->remove($roll);
        $this->rollRepository->saveRolls($sendToGlowingRolls);

        $this->eventDispatcher->dispatch(new RollsWereSentToGlowCheckInEvent(array_map(fn (Roll $roll) => $roll->getId(), $sendToGlowingRolls)));
    }
}
