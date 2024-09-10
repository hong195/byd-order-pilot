<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\CopyRollHistory;

use App\Orders\Domain\Service\Roll\History\HistorySyncService;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class CopyRollHistoryCommandHandler implements CommandHandlerInterface
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
     * @param CopyRollHistoryCommand $command the change order priority command instance
     */
    public function __invoke(CopyRollHistoryCommand $command): void
    {
        $this->historySyncService->copyHistory($command->rollId, $command->newRollIds);
    }
}
