<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindRolls;

use App\ProductionProcess\Application\Service\Roll\RollListService;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

final readonly class FindRollsHandler implements QueryHandlerInterface
{
    /**
     * Constructor for the class.
     *
     * @param AccessControlService $accessControlService the access control service dependency
     * @param RollListService      $rollListService      the roll list service dependency
     */
    public function __construct(private AccessControlService $accessControlService, private RollListService $rollListService)
    {
    }

    /**
     * Invokes the class to find rolls data based on a query and returns the printed products process detail result.
     *
     * @param FindRollsQuery $query the query for finding rolls
     *
     * @return FindRollsResult the result containing the detail of printed products process
     */
    public function __invoke(FindRollsQuery $query): FindRollsResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $rollDataList = $this->rollListService->getList(Process::from($query->process));

        return new FindRollsResult($rollDataList);
    }
}
