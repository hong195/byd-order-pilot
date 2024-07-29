<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Command\ManuallyAddOrder;

use App\Rolls\Application\AccessControll\AccessControlService;
use App\Rolls\Domain\Aggregate\Order\Order;
use App\Rolls\Domain\Service\ManualOrderService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class ManuallyAddOrderCommandHandler.
 */
final readonly class ManuallyAddOrderCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param AccessControlService $accessControlService    the access control service
     * @param ManualOrderService   $manuallyAddOrderService the manual order service
     */
    public function __construct(private AccessControlService $accessControlService, private ManualOrderService $manuallyAddOrderService)
    {
    }

    /**
     * Handles the ManuallyAddOrderCommand and returns the ID of the created order.
     *
     * @param ManuallyAddOrderCommand $command The command object containing the order details
     *
     * @return int The ID of the created order
     */
    public function __invoke(ManuallyAddOrderCommand $command): int
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to handle resource.');

        $order = $this->manuallyAddOrderService->add(
            priority: $command->priority,
			productType: $command->productType,
			length: $command->length,
			laminationType: $command->laminationType,
			rollType: $command->rollType,
			orderNumber: $command->orderNumber
        );

        if ($command->cutFileId) {
            $this->manuallyAddOrderService->attachFile($order, $command->cutFileId, Order::CUT_FILE);
        }

        if ($command->printFileId) {
            $this->manuallyAddOrderService->attachFile($order, $command->printFileId, Order::PRINT_FILE);
        }

        return $order->getId();
    }
}
