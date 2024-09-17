<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases;

use App\Inventory\Application\UseCases\Query\FindAFilm\FindAFilmQuery;
use App\Inventory\Application\UseCases\Query\FindAFilm\FindAFilmResult;
use App\Inventory\Application\UseCases\Query\FindFilmHistory\FindFilmHistoryQuery;
use App\Inventory\Application\UseCases\Query\FindFilmHistory\FindFilmHistoryQueryResult;
use App\Inventory\Application\UseCases\Query\FindFilms\FindFilmsQuery;
use App\Inventory\Application\UseCases\Query\FindFilms\FindFilmsResult;
use App\Shared\Application\Query\QueryBusInterface;

/**
 * Class PrivateCommandInteractor.
 */
readonly class PrivateQueryInteractor
{
	/**
	 * Constructor.
	 *
	 * @param QueryBusInterface $queryBus The query bus instance.
	 */
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    /**
     * Finds a film by its ID.
     *
     * @param int $id the ID of the film
     *
     * @return FindAFilmResult the film data
     */
    public function findAFilm(int $id): FindAFilmResult
    {
        $command = new FindAFilmQuery($id);

        return $this->queryBus->execute($command);
    }

    /**
     * Finds films based on the given film type.
     *
     * @param string $inventoryType the film type
     *
     * @return FindFilmsResult the array of found films
     */
    public function findFilms(string $inventoryType): FindFilmsResult
    {
        $command = new FindFilmsQuery($inventoryType);

        return $this->queryBus->execute($command);
    }

    /**
     * Finds films based on the given film type.
     *
     * @param FindFilmHistoryQuery $query the film type
     *
     * @return FindFilmHistoryQueryResult the array of found films
     */
    public function findFilmHistory(FindFilmHistoryQuery $query): FindFilmHistoryQueryResult
    {
        return $this->queryBus->execute($query);
    }
}
