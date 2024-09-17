<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Command\RecordInventoryAdding;


use App\Inventory\Application\UseCases\Services\HistoryService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

final readonly class RecordInventoryAddingCommandHandler implements CommandHandlerInterface
{
	public function __construct(private AccessControlService $accessControlService, private HistoryService $historyService)
	{
	}

	public function __invoke(RecordInventoryAddingCommand $command): void
	{
		AssertService::true($this->accessControlService->isGranted(), 'Access denied');
		$this->historyService->recordAdding($command->filmId, $command->event);
	}
}
