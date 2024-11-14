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
    public function getAvailableFilms(): Collection;

    /**
     * This method retrieves a film by its ID.
     *
     * @param int $filmId The ID of the film to retrieve
     *
     * @return FilmData The film data
     */
    public function getByFilmId(int $filmId): FilmData;
}
