<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\ManuallyAddOrder;

use App\Orders\Application\DTO\Order\ManualCreateOrderDTO;
use App\Orders\Application\Service\Order\ManualOrderService;
use App\Shared\Application\AccessControll\AccessControlService;
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
     *
     * @throws \Exception
     */
    public function __invoke(ManuallyAddOrderCommand $command): int
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to handle resource.');

        $orderData = new ManualCreateOrderDTO(
            customerName: $command->customerName,
            customerNotes: $command->customerNotes,
            packagingInstructions: $command->packagingInstructions,
        );

        $order = $this->manuallyAddOrderService->create($orderData);

        return $order->getId();
    }
}
