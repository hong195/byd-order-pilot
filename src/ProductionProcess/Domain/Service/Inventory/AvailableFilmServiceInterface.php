<?php

namespace App\ProductionProcess\Domain\Service\Inventory;

use App\ProductionProcess\Domain\DTO\FilmData;
use Doctrine\Common\Collections\Collection;

/**
 * Interface AvailableFilmServiceInterface.
 */
interface AvailableFilmServiceInterface
{
    /**
     * This method retrieves the available items of a given roll type.
     *
     * @return Collection<FilmData> The available films
     */
    public function getAvailableFilmsByType(string $filmType, float $minSize = 0): Collection;

    /**
     * This method retrieves the available items of a given roll type.
     *
     * @return Collection<FilmData> The available films
     */
    public function getAvailableFilms(): Collection;

    /**
     * This method retrieves a film by its ID.
     *
     * @param string $filmId The ID of the film to retrieve
     *
     * @return ?FilmData The film data
     */
    public function getByFilmId(string $filmId): ?FilmData;
}
