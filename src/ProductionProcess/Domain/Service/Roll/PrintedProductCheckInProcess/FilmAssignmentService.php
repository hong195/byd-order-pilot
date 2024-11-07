<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

use App\ProductionProcess\Domain\DTO\FilmData;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Groups\FilmGroup;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Groups\ProductGroup;
use Doctrine\Common\Collections\Collection;

final class FilmAssignmentService
{
    /**
     * @var FilmGroup[]
     */
    public array $filmGroups = [];

    /**
     * Constructor method for the class.
     *
     * @param AvailableFilmServiceInterface $availableFilmService An object of AvailableFilmServiceInterface
     * @param FilmGroup                     $filmGroup            An object of FilmGroup
     */
    public function __construct(private AvailableFilmServiceInterface $availableFilmService, private FilmGroup $filmGroup)
    {
    }

    /**
     * Assign films to order groups based on the film type and total length of each group.
     *
     * @param ProductGroup[] $groups An array of order groups to assign films to
     *
     * @return FilmGroup[] An array of order groups with assigned films
     */
    public function assignFilmToProductGroups(array $groups): array
    {
        foreach ($groups as $group) {
            $availableFilms = $this->getAvailableFilms($group->filmType, $group->getLength());

            if ($availableFilms->isEmpty()) {
                $this->handleNoAvailableFilms($group);
                continue;
            }

            foreach ($availableFilms as $film) {
                if (!isset($this->filmGroups[$film->id])) {
                    $this->filmGroups[$film->id] =
                        $this->filmGroup->make(
                            filmId: $film->id,
                            filmType: $film->filmType,
                            groups: [$group]
                        );

                    continue;
                }

                $filmGroup = $this->filmGroups[$film->id];

                if ($filmGroup->getTotalLength() + $group->getLength() <= $film->length) {
                    $filmGroup->addProductGroup($group);
                    continue;
                }

                $this->handleNoAvailableFilms($group);
            }
        }

        return array_values($this->filmGroups);
    }

    /**
     * Retrieves an available film based on the given film type and minimum length.
     *
     * @param string $filmType  The type of the film to search for
     * @param float  $minLength The minimum length required for the film
     *
     * @return Collection<FilmData> The available FilmData object matching the criteria, or null if none found
     */
    private function getAvailableFilms(string $filmType, float $minLength): Collection
    {
        $films = $this->availableFilmService->getAvailableFilms();

        return $films->filter(function (FilmData $film) use ($filmType, $minLength) {
            return $film->filmType === $filmType && $film->length >= $minLength;
        });
    }

    /**
     * Handles the case when no available films are found for a specific film group.
     *
     * @param mixed $group The film group which does not have any available films
     */
    private function handleNoAvailableFilms($group): void
    {
        if (!isset($this->filmGroups[null])) {
            $this->filmGroups[null] = $this->filmGroup->make(null, null, [$group]);
        } else {
            $this->filmGroups[null]->addProductGroup($group);
        }
    }
}
