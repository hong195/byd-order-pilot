<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Exceptions\OrderReprintException;
use App\ProductionProcess\Domain\Repository\OrderRepositoryInterface;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\Roll\JobCheckInProcess\JobCheckInInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ReprintPrintedProduct.
 *
 * This class handles the printing of orders.
 */
final readonly class ReprintRoll
{
//    /**
//     * Class constructor.
//     *
//     * @param RollRepositoryInterface  $rollRepository  the roll repository interface
//     * @param OrderRepositoryInterface $orderRepository the order repository interface
//     * @param PrintedProductCheckInInterface   $ordersCheckIn   the orders check-in interface
//     */
//    public function __construct(private RollRepositoryInterface $rollRepository, private OrderRepositoryInterface $orderRepository, private PrintedProductCheckInInterface $ordersCheckIn)
//    {
//    }

    /**
     * Handle the order reprint.
     *
     * @param int $rollId The ID of the roll
     *
     * @throws NotFoundHttpException If the roll with the specified ID is not found
     * @throws OrderReprintException
     */
    public function handle(int $rollId): void
    {
//        $roll = $this->rollRepository->findById($rollId);
//
//        if (!$roll) {
//            throw new NotFoundHttpException('Roll not found');
//        }
//
//        foreach ($roll->getOrders() as $order) {
//            $order->reprint();
//            $this->orderRepository->save($order);
//        }
//
//        $this->rollRepository->remove($roll);
//
//        $this->ordersCheckIn->checkIn();
    }
}
