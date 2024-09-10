<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\SyncRollHistory;

use App\Orders\Domain\Service\Roll\History\HistorySyncService;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class SyncRollHistoryCommandHandler implements CommandHandlerInterface
{
    /**
     * Class MyClass.
     */
    public function __construct(private HistorySyncService $historySyncService)
    {
    }

    /**
     * Invokes the command to change the order priority.
     *
     * @param SyncRollHistoryCommand $command the change order priority command instance
     */
    public function __invoke(SyncRollHistoryCommand $command): void
    {
        $this->historySyncService->sync($command->rollId);
    }
}
