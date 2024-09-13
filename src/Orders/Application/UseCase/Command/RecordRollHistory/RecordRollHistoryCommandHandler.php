<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\RecordRollHistory;

use App\Orders\Domain\Aggregate\Roll\History\Type;
use App\Orders\Domain\Service\Roll\History\HistorySyncService;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class RecordRollHistoryCommandHandler implements CommandHandlerInterface
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
     * @param RecordRollHistoryCommand $command the change order priority command instance
     */
    public function __invoke(RecordRollHistoryCommand $command): void
    {
        $this->historySyncService->record($command->rollId, $command->getType());
    }
}
