<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindRolls;

use App\Orders\Application\DTO\RollDataTransformer;
use App\Orders\Domain\Repository\RollFilter;
use App\Orders\Domain\Repository\RollRepositoryInterface;
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
     * Class Constructor.
     *
     * @param AccessControlService    $accessControlService the Access Control Service object
     * @param RollRepositoryInterface $rollRepository       the Roll Repository Interface object
     * @param RollDataTransformer     $rollDataTransformer  the Roll Data Transformer object
     */
    public function __construct(private AccessControlService $accessControlService, private RollRepositoryInterface $rollRepository, private RollDataTransformer $rollDataTransformer)
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
        $rollFilterQuery = new RollFilter();
        $rolls = $this->rollRepository->findByFilter($rollFilterQuery);

        $rollsData = $this->rollDataTransformer->fromRollsEntityList($rolls);

        return new FindRollsResult($rollsData);
    }
}
