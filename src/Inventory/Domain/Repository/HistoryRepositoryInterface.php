<?php

namespace App\Inventory\Domain\Repository;

use App\Inventory\Domain\Aggregate\History;
use App\Shared\Domain\Repository\PaginationResult;

interface HistoryRepositoryInterface
{
    /**
     * Adds a new history record.
     *
     * @param History $history The history record to add
     */
    public function add(History $history): void;

    /**
     * Finds records by film id.
     *
     * @param int $filmId The id of the film
     *
     * @return History[]
     */
    public function findByFilmId(int $filmId): array;

    /**
     * Finds records by film type.
     *
     * @param string $filmType The type of the film
     *
     * @return History[]
     */
    public function findByFilmType(string $filmType): array;

    /**
     * Finds records by filter.
     *
     * @param HistoryFilter $filter
     *
     * @return PaginationResult
     */
    public function findByFilter(HistoryFilter $filter): PaginationResult;
}
