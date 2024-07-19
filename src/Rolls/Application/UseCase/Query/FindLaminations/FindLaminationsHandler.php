<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Query\FindLaminations;

use App\Rolls\Application\DTO\LaminationDataTransformer;
use App\Rolls\Infrastructure\Repository\LaminationRepository;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 * Class FindLaminationsHandler.
 *
 * This class is responsible for handling the FindLaminationsQuery and returning the result.
 */
final readonly class FindLaminationsHandler implements QueryHandlerInterface
{
    public function __construct(
        private LaminationRepository $laminationRepository,
        private LaminationDataTransformer $laminationDataTransformer
    ) {
    }

    /**
     * Invokes the method to find laminations based on the provided query.
     *
     * @param FindLaminationsQuery $laminationsQuery the query object containing the page information
     *
     * @return FindLaminationsResult the result object containing the laminations data
     */
    public function __invoke(FindLaminationsQuery $laminationsQuery): FindLaminationsResult
    {
        $laminations = $this->laminationRepository->findPagedItems($laminationsQuery->page);

        $laminationsData = $this->laminationDataTransformer->fromRollsEntityList($laminations->items);

        return new FindLaminationsResult($laminationsData);
    }
}
