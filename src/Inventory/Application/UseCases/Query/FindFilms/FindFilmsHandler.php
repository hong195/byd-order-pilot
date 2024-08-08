<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\FindFilms;

use App\Inventory\Application\UseCases\Query\DTO\FilmDataTransformer;
use App\Inventory\Domain\Repository\FilmFilter;
use App\Inventory\Domain\Repository\FilmRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 * FindFilmsHandler constructor.
 *
 * @param FilmRepositoryInterface $FilmRepository
 * @param FilmDataTransformer     $FilmDataTransformer
 */
final readonly class FindFilmsHandler implements QueryHandlerInterface
{
    public function __construct(private FilmRepositoryInterface $FilmRepository, private FilmDataTransformer $FilmDataTransformer)
    {
    }

    /**
     * Executes the FindFilmsQuery and returns the result.
     *
     * @param FindFilmsQuery $FilmQuery the query object used to find Films
     *
     * @return FindFilmsResult the result object containing the Films data
     */
    public function __invoke(FindFilmsQuery $FilmQuery): FindFilmsResult
    {
        $filmFilter = new FilmFilter($FilmQuery->filmType);
        $films = $this->FilmRepository->findQueried($filmFilter);

        $FilmsData = $this->FilmDataTransformer->fromFilmList($films);

        return new FindFilmsResult($FilmsData);
    }
}
