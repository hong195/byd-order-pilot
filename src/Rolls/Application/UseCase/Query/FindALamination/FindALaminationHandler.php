<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Query\FindALamination;

use App\Rolls\Application\DTO\LaminationDataTransformer;
use App\Rolls\Application\UseCase\Query\FindARoll\FindALaminationResult;
use App\Rolls\Infrastructure\Repository\LaminationRepository;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handles the FindARollQuery and returns the FindARollResult.
 */
final readonly class FindALaminationHandler implements QueryHandlerInterface
{
    public function __construct(
        private LaminationRepository $laminationRepository,
        private LaminationDataTransformer $dataTransformer
    ) {
    }

    /**
     * Invokes the FindARollQuery and returns the FindARollResult.
     *
     * @param FindALaminationQuery $findALaminationQuery the query used to find the roll
     *
     * @return FindALaminationResult the result of finding the roll
     */
    public function __invoke(FindALaminationQuery $findALaminationQuery): FindALaminationResult
    {
        $lamination = $this->laminationRepository->findById($findALaminationQuery->laminationId);

        if (is_null($lamination)) {
            throw new NotFoundHttpException();
        }

        $lamination = $this->dataTransformer->fromEntity($lamination);

        return new FindALaminationResult($lamination);
    }
}
