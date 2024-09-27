<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\ChangePrintedProductSort;

use App\ProductionProcess\Domain\Service\PrintedProduct\ChangeSortOrder;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Handles the change order sort command.
 */
readonly class ChangePrintedProductSortCommandHandler implements CommandHandlerInterface
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
     * @param ChangePrintedProductSortCommand $command the ChangeOrderSortCommand instance
     */
    public function __invoke(ChangePrintedProductSortCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Cannot change order sort.');

        $this->changeSortOrder->handle(rollId: $command->rollId, group: $command->group, sortOrders: $command->sortOrders);
    }
}
