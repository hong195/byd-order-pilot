<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\FindFilmHistory;

use App\Inventory\Application\UseCases\Query\DTO\FilmHistoryDataTransformer;
use App\Inventory\Domain\Repository\HistoryFilter;
use App\Inventory\Domain\Repository\HistoryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Repository\Pager;

final readonly class FindFilmHistoryQueryHandler implements QueryHandlerInterface
{
    /**
     * Constructor method for the class.
     *
     * @param HistoryRepositoryInterface $historyRepository - The instance of HistoryRepositoryInterface injected into the class
     */
    public function __construct(private HistoryRepositoryInterface $historyRepository, private FilmHistoryDataTransformer $transformer)
    {
    }

    /**
     * Invokable method for the class.
     *
     * @param FindFilmHistoryQuery $command - The instance of FindFilmHistoryQuery given to the method
     */
    public function __invoke(FindFilmHistoryQuery $command): FindFilmHistoryQueryResult
    {
        $filter = new HistoryFilter(
            pager: new Pager(
                page: $command->page,
                perPage: $command->perPage
            ),
            inventoryType: $command->inventoryType,
            filmId: $command->filmId,
            event: $command->event,
            type: $command->type,
            interval: $command->interval
        );

        $result = $this->historyRepository->findByFilter($filter);

		$dto = $this->transformer->fromHistoryList($result->items);

        return new FindFilmHistoryQueryResult(
            items: $dto,
            total: $result->total,
        );
    }
}
