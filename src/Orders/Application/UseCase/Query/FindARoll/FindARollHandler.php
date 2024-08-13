<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindARoll;

use App\Orders\Application\DTO\RollDataTransformer;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handles the FindARollQuery and returns the FindARollResult.
 */
final readonly class FindARollHandler implements QueryHandlerInterface
{
    /**
     * Constructor for the class.
     *
     * @param RollRepositoryInterface $rollRepository  The roll repository interface
     * @param RollDataTransformer     $dataTransformer The roll data transformer
     */
    public function __construct(private RollRepositoryInterface $rollRepository, private RollDataTransformer $dataTransformer)
    {
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
