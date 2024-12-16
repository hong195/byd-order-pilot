<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\ChangePrintedProductSort;

use App\ProductionProcess\Domain\Service\PrintedProduct\ChangeSortOrder;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * Handles the change order sort command.
 */
readonly class ChangePrintedProductSortCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param ChangeSortOrder $changeSortOrder the ChangeSortOrder instance
     */
    public function __construct(private ChangeSortOrder $changeSortOrder)
    {
    }

    /**
     * __invoke method of the class.
     *
     * @param ChangePrintedProductSortCommand $command the ChangeOrderSortCommand instance
     */
    public function __invoke(ChangePrintedProductSortCommand $command): void
    {
        $this->changeSortOrder->handle(rollId: $command->rollId, group: $command->group, sortOrders: $command->sortOrders);
    }
}
