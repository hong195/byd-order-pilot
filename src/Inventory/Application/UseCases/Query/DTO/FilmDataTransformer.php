<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\DTO;

use App\Inventory\Domain\Aggregate\AbstractFilm;

final readonly class FilmDataTransformer
{
    /**
     * Converts an AbstractFilm entity to FilmData object.
     *
     * @param AbstractFilm $film the AbstractFilm entity to convert
     *
     * @return FilmData the FilmData object created from the AbstractFilm entity
     */
    public function makeFromEntity(AbstractFilm $film): FilmData
    {
        return new FilmData(
            id: $film->getId(),
            name: $film->getName(),
            length: round($film->getLength(), 2),
            type: $film->getType()
        );
    }

    /**
     * Transforms a list of film entities into a list of film data.
     *
     * @param AbstractFilm[] $films the list of film entities
     *
     * @return FilmData[] the list of film data
     */
    public function fromFilmList(array $films): array
    {
        $filmDataList = [];
        foreach ($films as $film) {
            $filmDataList[] = $this->makeFromEntity($film);
        }

        return $filmDataList;
    }
}
