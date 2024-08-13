<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindRolls;

use App\Orders\Application\DTO\RollDataTransformer;
use App\Orders\Domain\Repository\RollFilter;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 * FindRollsHandler constructor.
 *
 * @param RollRepositoryInterface $rollRepository
 * @param RollDataTransformer     $rollDataTransformer
 */
final readonly class FindRollsHandler implements QueryHandlerInterface
{
    public function __construct(private RollRepositoryInterface $rollRepository, private RollDataTransformer $rollDataTransformer)
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
        $rollFilterQuery = new RollFilter();
        $rolls = $this->rollRepository->findByFilter($rollFilterQuery);

        $rollsData = $this->rollDataTransformer->fromRollsEntityList($rolls);

        return new FindRollsResult($rollsData);
    }
}
