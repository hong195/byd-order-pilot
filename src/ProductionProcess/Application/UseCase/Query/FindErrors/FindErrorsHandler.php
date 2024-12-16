<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindErrors;

use App\ProductionProcess\Application\DTO\Error\ErrorDataTransformer;
use App\ProductionProcess\Infrastructure\Repository\ErrorRepository;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 * Handler for finding errors based on a query.
 */
final readonly class FindErrorsHandler implements QueryHandlerInterface
{
    /**
     * Constructor for the class.
     */
    public function __construct(private ErrorRepository $repository, private ErrorDataTransformer $transformer)
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
        $result = $this->repository->findByFilter($query->getErrorFilter());

        return new FindErrorsResult($this->transformer->fromErrorsList($result));
    }
}
