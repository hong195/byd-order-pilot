<?php

declare(strict_types=1);

/**
 * Handler for finding errors based on a query.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FindEmployeeErrors;

use App\ProductionProcess\Application\Service\Roll\Error\EmployeeErrorCountListService;
use App\ProductionProcess\Infrastructure\Repository\ErrorRepository;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 * Handler for finding errors based on a query.
 */
final readonly class FindEmployeeErrorsHandler implements QueryHandlerInterface
{
    /**
     * Constructor for the class.
     */
    public function __construct(private ErrorRepository $repository, private EmployeeErrorCountListService $employeeErrorCountListService)
    {
    }

    /**
     * Invokes the handler to find errors based on the provided query.
     *
     * @param FindEmployeeErrorsQuery $query the query containing filter criteria
     *
     * @return FindEmployeeErrorsResult the result of finding errors
     */
    public function __invoke(FindEmployeeErrorsQuery $query): FindEmployeeErrorsResult
    {
        $result = $this->repository->findEmployeeErrorsByDateRangeFilter($query->dateRangeFilter);

        $result = ($this->employeeErrorCountListService)($result);

        return new FindEmployeeErrorsResult($result);
    }
}
