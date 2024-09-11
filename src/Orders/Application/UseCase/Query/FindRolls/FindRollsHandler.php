<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindRolls;

use App\Orders\Application\DTO\RollDataTransformer;
use App\Orders\Application\Service\Roll\RollListService;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Orders\Domain\ValueObject\Process;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * FindRollsHandler constructor.
 *
 * @param RollRepositoryInterface $rollRepository
 * @param RollDataTransformer     $rollDataTransformer
 */
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
     * Executes the FindRollsQuery and returns the result.
     *
     * @param FindRollsQuery $rollQuery the query object used to find rolls
     *
     * @return FindRollsResult the result object containing the rolls data
     */
    public function __invoke(FindRollsQuery $rollQuery): FindRollsResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $rollDataList = $this->rollListService->getList(Process::from($rollQuery->process));

        return new FindRollsResult($rollDataList);
    }
}
