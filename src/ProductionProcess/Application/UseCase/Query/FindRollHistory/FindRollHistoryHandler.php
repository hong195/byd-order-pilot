<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindRollHistory;

use App\ProductionProcess\Application\DTO\RollDataTransformer;
use App\ProductionProcess\Application\Service\Roll\History\HistoryListService;
use App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 * FindRollHistoryHandler constructor.
 *
 * @param RollRepositoryInterface $rollRepository
 * @param RollDataTransformer     $rollDataTransformer
 */
final readonly class FindRollHistoryHandler implements QueryHandlerInterface
{
    /**
     * Class constructor.
     */
    public function __construct(private HistoryListService $historyListService)
    {
    }

    /**
     * Executes the FindRollHistoryQuery and returns the result.
     *
     * @param FindRollHistoryQuery $rollQuery the query object used to find rolls
     *
     * @return FindRollHistoryResult the result object containing the rolls data
     */
    public function __invoke(FindRollHistoryQuery $rollQuery): FindRollHistoryResult
    {
        return new FindRollHistoryResult(($this->historyListService)($rollQuery->rollId));
    }
}
