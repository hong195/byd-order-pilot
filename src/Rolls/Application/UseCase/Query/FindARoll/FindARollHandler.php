<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Query\FindARoll;

use App\Rolls\Application\DTO\RollDataTransformer;
use App\Rolls\Domain\Repository\RollRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handles the FindARollQuery and returns the FindARollResult.
 */
final readonly class FindARollHandler implements QueryHandlerInterface
{
    public function __construct(
        private RollRepositoryInterface $rollRepository,
        private RollDataTransformer $dataTransformer
    ) {
    }

    /**
     * Invokes the FindARollQuery and returns the FindARollResult.
     *
     * @param FindARollQuery $rollQuery the query used to find the roll
     *
     * @return FindARollResult the result of finding the roll
     */
    public function __invoke(FindARollQuery $rollQuery): FindARollResult
    {
        $roll = $this->rollRepository->findById($rollQuery->rollId);

        if (is_null($roll)) {
            throw new NotFoundHttpException();
        }

        $rollData = $this->dataTransformer->fromEntity($roll);

        return new FindARollResult($rollData);
    }
}
