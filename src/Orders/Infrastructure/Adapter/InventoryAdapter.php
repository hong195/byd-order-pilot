<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Adapter;

use App\Orders\Domain\DTO\FilmData;
use App\Orders\Domain\DTO\FilmDataTransformer;
use App\Orders\Domain\Service\AvailableFilmServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class InventoryAdapter implements AvailableFilmServiceInterface
{
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
}
