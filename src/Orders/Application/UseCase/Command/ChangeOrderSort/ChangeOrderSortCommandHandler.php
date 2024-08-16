<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\ChangeOrderSort;

use App\Orders\Domain\Service\Order\ChangeSortOrder;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Handles the change order sort command.
 */
readonly class ChangeOrderSortCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService $accessControlService the AccessControlService instance
     * @param ChangeSortOrder      $changeSortOrder      the ChangeSortOrder instance
     */
    public function __construct(private AccessControlService $accessControlService, private ChangeSortOrder $changeSortOrder)
    {
    }

    /**
     * __invoke method of the class.
     *
     * @param ChangeOrderSortCommand $command the ChangeOrderSortCommand instance
     */
    public function __invoke(ChangeOrderSortCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Cannot change order sort.');

        $this->changeSortOrder->handle(rollId: $command->rollId, group: $command->group, sortOrders: $command->sortOrders);
    }
}
