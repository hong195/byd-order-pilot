<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service;

use App\Orders\Domain\Exceptions\ShipAndCollectionException;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Orders\Domain\ValueObject\Process;
use App\Orders\Domain\ValueObject\Status;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CutCheckInService.
 *
 * This class handles the cutting of a roll.
 */
final readonly class ShipAndCollectOrdersService
{
    /**
     * Construct the class.
     *
     * @param RollRepositoryInterface $rollRepository the roll repository
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private OrderRepositoryInterface $orderRepository)
    {
    }

    /**
     * @throws ShipAndCollectionException
     */
    public function handle(int $rollId): void
    {
        $roll = $this->rollRepository->findById($rollId);

        if (!$roll) {
            throw new NotFoundHttpException('Roll not found');
        }

        if ($roll->getOrders()->isEmpty()) {
            throw new NotFoundHttpException('No orders found!');
        }

        if (!$roll->getProcess()->equals(Process::CUTTING_CHECK_IN)) {
            throw new ShipAndCollectionException('Orders cant be send to ship be collected!');
        }

        foreach ($roll->getOrders() as $order) {
            $order->changeStatus(Status::SHIP_AND_COLLECT);
            $this->orderRepository->save($order);
        }

        $roll->updateProcess(Process::CUT);

        $this->rollRepository->save($roll);
    }
}
