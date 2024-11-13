<?php

declare(strict_types=1);

/**
 * Class HistoryListService.
 */

namespace App\ProductionProcess\Application\Service\Roll\History;

use App\ProductionProcess\Application\DTO\HistoryData;
use App\ProductionProcess\Domain\Aggregate\Roll\History\History;

/**
 * Class HistoryStatisticsListService.
 */
final readonly class HistoryStatisticsListService
{
    /**
     * @param History[] $histories
     */
    public function __construct(private array $histories)
    {
    }

    /**
     * Retrieves the history data for a given roll ID.
     *
     * @return HistoryData[] an array of HistoryData objects
     */
    public function __invoke(): array
    {
        $historyData = [];
        foreach ($this->histories as $history) {
            $historyData[] = new HistoryData(
                id: $history->getId(),
                rollId: $history->rollId,
                type: $history->type->value,
                process: $history->process->value,
                happenedAt: $history->getHappenedAt()
            );
        }

        return $historyData;
    }
}
