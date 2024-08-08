<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\FindAFilm;

use App\Inventory\Application\UseCases\Query\DTO\FilmDataTransformer;
use App\Inventory\Domain\Repository\FilmRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handles the FindAFilmQuery and returns the FindAFilmResult.
 */
final readonly class FindAFilmHandler implements QueryHandlerInterface
{
    public function __construct(
        private FilmRepositoryInterface $FilmRepository,
        private FilmDataTransformer $dataTransformer
    ) {
    }

    /**
     * Invokes the FindAFilmQuery and returns the FindAFilmResult.
     *
     * @param FindAFilmQuery $filmId the query used to find the Film
     *
     * @return FindAFilmResult the result of finding the Film
     */
    public function __invoke(FindAFilmQuery $filmId): FindAFilmResult
    {
        $film = $this->FilmRepository->findById($filmId->filmId);

        if (is_null($film)) {
            throw new NotFoundHttpException('Film not found');
        }

        $filmData = $this->dataTransformer->makeFromEntity($film);

        return new FindAFilmResult($filmData);
    }
}
