<?php

declare(strict_types=1);

/**
 * Handler for finding errors based on a query.
 */

namespace App\ProductionProcess\Application\UseCase\Query\FindEmployerErrors;

use App\ProductionProcess\Infrastructure\Repository\ErrorRepository;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Handler for finding errors based on a query.
 */
final readonly class FindEmployerErrorsHandler implements QueryHandlerInterface
{
    /**
     * Constructor for the class.
     *
     * @param AccessControlService $accessControlService the access control service dependency
     * @param ErrorRepository      $repository
     */
    public function __construct(private AccessControlService $accessControlService, private ErrorRepository $repository)
    {
    }

    /**
     * Invokes the handler to find errors based on the provided query.
     *
     * @param FindEmployerErrorsQuery $query the query containing filter criteria
     *
     * @return FindEmployerErrorsResult the result of finding errors
     */
    public function __invoke(FindEmployerErrorsQuery $query): FindEmployerErrorsResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the query');

        $result = $this->repository->findEmployerErrorsByFilter($query->getErrorFilter());

        return new FindEmployerErrorsResult($result);
    }
}
