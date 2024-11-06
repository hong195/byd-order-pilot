<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindErrors;

use App\ProductionProcess\Application\DTO\Error\ErrorDataTransformer;
use App\ProductionProcess\Infrastructure\Repository\ErrorRepository;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Handler for finding errors based on a query.
 */
final readonly class FindErrorsHandler implements QueryHandlerInterface
{
    /**
     * Constructor for the class.
     *
     * @param AccessControlService $accessControlService the access control service dependency
     * @param ErrorRepository      $repository
     * @param ErrorDataTransformer $transformer
     */
    public function __construct(private AccessControlService $accessControlService, private ErrorRepository $repository, private ErrorDataTransformer $transformer)
    {
    }

    /**
     * Invokes the handler to find errors based on the provided query.
     *
     * @param FindErrorsQuery $query the query containing filter criteria
     *
     * @return FindErrorsResult the result of finding errors
     */
    public function __invoke(FindErrorsQuery $query): FindErrorsResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the query');

        $result = $this->repository->findByFilter($query->getErrorFilter());

        return new FindErrorsResult($this->transformer->fromErrorsList($result));
    }
}
