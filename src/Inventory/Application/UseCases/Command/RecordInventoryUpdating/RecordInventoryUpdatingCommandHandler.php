<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Command\RecordInventoryUpdating;

use App\Inventory\Application\UseCases\Query\DTO\RecordHistoryUpdatingData;
use App\Inventory\Application\UseCases\Services\HistoryService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

final readonly class RecordInventoryUpdatingCommandHandler implements CommandHandlerInterface
{
    public function __construct(private AccessControlService $accessControlService, private HistoryService $historyService)
    {
    }

    public function __invoke(RecordInventoryUpdatingCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $recordHistoryUpdatingDto = new RecordHistoryUpdatingData(
            filmId: $command->filmId,
            event: $command->event,
            oldSize: round($command->oldSize, 2),
            newSize: round($command->newSize, 2)
        );

        $this->historyService->recordUpdating($recordHistoryUpdatingDto);
    }
}
