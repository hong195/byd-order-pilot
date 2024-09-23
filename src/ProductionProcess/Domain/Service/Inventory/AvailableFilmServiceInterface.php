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
}
