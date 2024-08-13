<?php

declare(strict_types=1);

namespace App\Inventory\Api;

use App\Inventory\Application\UseCases\PrivateQueryInteractor;
use App\Inventory\Application\UseCases\Query\DTO\FilmData;
use App\Inventory\Domain\Aggregate\FilmType;
use App\Orders\Infrastructure\Adapter\InventoryApiInterface;

final readonly class InventoryApi implements InventoryApiInterface
{
    public function __construct(private PrivateQueryInteractor $privateQueryInteractor)
    {
    }

    /**
     * Retrieves the available films.
     *
     * @return FilmData[] the available films
     */
    public function getAvailableFilms(): array
    {
        return $this->privateQueryInteractor->findFilms(FilmType::ROLL->value)->items;
    }
}
