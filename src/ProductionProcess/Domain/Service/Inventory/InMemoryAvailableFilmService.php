<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Inventory;

use App\ProductionProcess\Domain\DTO\FilmData;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /**
     * Retrieves a film by its ID.
     *
     * @param int $filmId The ID of the film to retrieve
     *
     * @return FilmData The film data
     */
    public function getByFilmId(int $filmId): FilmData
    {
        $films = $this->getAvailableFilms();

        foreach ($films as $film) {
            if ($film->filmId === $filmId) {
                return $film;
            }
        }

        throw new NotFoundHttpException('Film not found');
    }
}
