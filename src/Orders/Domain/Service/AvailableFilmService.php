<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service;

use App\Orders\Domain\DTO\FilmData;
use App\Orders\Domain\ValueObject\RollType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Retrieves the available films for a given roll type.
 *
 * @param string $rollType The roll type for which to retrieve the available films
 *
 * @return Collection An array collection of FilmData objects representing the available films
 */
final readonly class AvailableFilmService implements AvailableFilmServiceInterface
{
    /**
     * Retrieves the available films for a given roll type.
     *
     * @return Collection An array collection of FilmData objects representing the available films
     */
    public function getAvailableFilms(): Collection
    {
        return new ArrayCollection([
            new FilmData(3, 'Test Roll Film3', 20, RollType::CHROME->value),
            new FilmData(4, 'Test Roll Film4', 20, RollType::CHROME->value),
            new FilmData(1, 'Test Roll Film', 15, RollType::NEON->value),
            new FilmData(2, 'Test Roll Film2', 20, RollType::NEON->value),
            new FilmData(5, 'Test Roll Film5', 20, RollType::CLEAR->value),
        ]);
    }
}
