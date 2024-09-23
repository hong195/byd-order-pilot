<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindRollHistory;

use App\ProductionProcess\Application\DTO\RollDataTransformer;
use App\ProductionProcess\Application\Service\Roll\History\HistoryListService;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

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
     *
     * @param AccessControlService $accessControlService the access control service instance
     */
    public function __construct(private AccessControlService $accessControlService, private HistoryListService $historyListService)
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
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        return new FindRollHistoryResult(($this->historyListService)($rollQuery->rollId));
    }
}
