<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Inventory;

use App\ProductionProcess\Domain\DTO\FilmData;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Retrieves the available films for a given roll type.
 *
 * @param string $filmType The roll type for which to retrieve the available films
 *
 * @return Collection An array collection of FilmData objects representing the available films
 */
final readonly class InMemoryAvailableFilmService implements AvailableFilmServiceInterface
{
    /**
     * Retrieves the available films for a given roll type.
     *
     * @return Collection An array collection of FilmData objects representing the available films
     */
    public function getAvailableFilms(): Collection
    {
        return new ArrayCollection([
            new FilmData(3, 'Test Roll Film3', 20, 'chrome'),
            new FilmData(4, 'Test Roll Film4', 20, 'chrome'),
            new FilmData(1, 'Test Roll Film', 15, 'neon'),
            new FilmData(5, 'Test Roll Film5', 20, 'clear'),
        ]);
    }
}
