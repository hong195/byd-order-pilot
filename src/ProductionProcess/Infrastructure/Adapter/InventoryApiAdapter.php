<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Adapter;

use App\ProductionProcess\Domain\DTO\FilmData;
use App\ProductionProcess\Domain\DTO\FilmDataTransformer;
use App\ProductionProcess\Domain\Service\Inventory\AvailableFilmServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * InventoryApiAdapter is an implementation of AvailableFilmServiceInterface.
 * It retrieves a collection of available films using the InventoryApiInterface,
 * transforms the film data using the FilmDataTransformer, and normalizes the
 * film data using the NormalizerInterface.
 */
final readonly class InventoryApiAdapter implements AvailableFilmServiceInterface
{
    /**
     * Class constructor.
     *
     * @param InventoryApiInterface $inventoryApi    the Inventory API instance
     * @param FilmDataTransformer   $dataTransformer the Film Data Transformer instance
     * @param NormalizerInterface   $normalizer      the Normalizer Interface instance
     */
    public function __construct(private InventoryApiInterface $inventoryApi, private FilmDataTransformer $dataTransformer, private NormalizerInterface $normalizer)
    {
    }

    /**
     * Retrieves a collection of available films.
     *
     * @return Collection<FilmData> The available films
     *
     * @throws ExceptionInterface
     */
    public function getAvailableFilms(): Collection
    {
        $films = $this->inventoryApi->getAvailableFilms();

        $films = $this->normalizer->normalize($films);

        return new ArrayCollection($this->dataTransformer->fromArrayList($films));
    }

    /**
     * Retrieves a film by its ID.
     *
     * @param int $filmId The ID of the film to retrieve
     *
     * @return FilmData The film data
     *
     * @throws ExceptionInterface
     */
    public function getByFilmId(int $filmId): FilmData
    {
        $film = $this->inventoryApi->getById($filmId);

        $filmArray = $this->normalizer->normalize($film);

        return $this->dataTransformer->fromArray(
            id: $filmArray['id'],
            name: $filmArray['name'],
            length: $filmArray['length'],
            type: $filmArray['type']
        );
    }
}
