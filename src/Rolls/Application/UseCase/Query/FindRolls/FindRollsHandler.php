<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Query\FindRolls;

use App\Rolls\Application\DTO\RollDataTransformer;
use App\Rolls\Domain\Repository\RollRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 *
 */
final readonly class FindRollsHandler implements QueryHandlerInterface
{
    public function __construct(
        private RollRepositoryInterface $rollRepository,
        private RollDataTransformer $rollDataTransformer
    ) {
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
        $rolls = $this->rollRepository->findPagedItems($rollQuery->page);

        $rollsData = $this->rollDataTransformer->fromRollsEntityList($rolls->items);

        return new FindRollsResult($rollsData);
    }
}
