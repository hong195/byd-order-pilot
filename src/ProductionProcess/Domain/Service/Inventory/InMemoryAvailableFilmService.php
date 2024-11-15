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
    private Collection $films;

    public function __construct()
    {
        $this->films = new ArrayCollection([
            new FilmData(3, 'Test Roll Film3', 20, 'chrome'),
            new FilmData(4, 'Test Roll Film4', 20, 'chrome'),
            new FilmData(1, 'Test Roll Film', 15, 'neon'),
            new FilmData(5, 'Test Roll Film5', 20, 'clear'),
        ]);
    }

    /**
     * Retrieves the available films for a given roll type.
     *
     * @return Collection<FilmData> An array collection of FilmData objects representing the available films
     */
    public function getAvailableFilms(string $filmType, float $minSize = 0): Collection
    {
        return $this->films->filter(function (FilmData $film) use ($filmType, $minSize) {
            return $film->filmType === $filmType && $film->length >= $minSize;
        });
    }

    /**
     * Retrieves a film by its ID.
     *
     * @param int $filmId The ID of the film to retrieve
     *
     * @return ?FilmData The film data
     */
    public function getByFilmId(int $filmId): ?FilmData
    {
        foreach ($this->films as $film) {
            if ($film->filmId === $filmId) {
                return $film;
            }
        }

        throw new NotFoundHttpException('Film not found');
    }
}
