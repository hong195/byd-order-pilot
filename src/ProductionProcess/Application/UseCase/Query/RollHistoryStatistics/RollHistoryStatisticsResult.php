<?php

/**
 * Handles the results for roll history statistics in the production process.
 */

namespace App\ProductionProcess\Application\UseCase\Query\RollHistoryStatistics;

use App\ProductionProcess\Domain\DTO\RollHistoryStatistics;

/**
 * Class RollHistoryStatisticsResult.
 *
 * Encapsulates the result of a roll history statistics query.
 */
final readonly class RollHistoryStatisticsResult
{
    /**
     * @param RollHistoryStatistics[] $results Array of RollHistoryStatistics DTOs
     */
    public function __construct(private array $results = [])
    {
    }

    /**
     * Returns the roll history statistics results.
     *
     * @return RollHistoryStatistics[] Array of RollHistoryStatistics DTOs
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * Converts the results array of RollHistoryStatistics DTOs into an array.
     *
     * @return array<array<string, mixed>> Array of associative arrays representing RollHistoryStatistics objects
     */
    public function toArray(): array
    {
        return array_map(function (RollHistoryStatistics $statistics) {
            return [
                'rollId' => $statistics->getRollId(),
                'process' => $statistics->getProcess(),
                'type' => $statistics->getType(),
                'happenedAt' => $statistics->getHappenedAt(),
            ];
        }, $this->results);
    }
}
