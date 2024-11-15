<?php

declare(strict_types=1);

/**
 * Handler for finding errors based on a query.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FindEmployerErrors;

use App\ProductionProcess\Application\Service\Roll\Error\EmployeeErrorCountListService;
use App\ProductionProcess\Infrastructure\Repository\ErrorRepository;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Handler for finding errors based on a query.
 */
final readonly class FindEmployeeErrorsHandler implements QueryHandlerInterface
{
    /**
     * Constructor for the class.
     *
     * @param AccessControlService          $accessControlService          the access control service dependency
     * @param ErrorRepository               $repository
     * @param EmployeeErrorCountListService $employeeErrorCountListService
     */
    public function __construct(private AccessControlService $accessControlService, private ErrorRepository $repository, private EmployeeErrorCountListService $employeeErrorCountListService)
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
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the query');

        $result = $this->repository->findEmployerErrorsByDateRangeFilter($query->dateRangeFilter);

        $result = ($this->employeeErrorCountListService)($result);

        return new FindEmployeeErrorsResult($result);
    }
}
