<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\Api;

use App\Inventory\Application\UseCases\PrivateQueryInteractor;
use App\Inventory\Application\UseCases\Query\DTO\FilmData;
use App\Inventory\Domain\Aggregate\FilmType;
use App\ProductionProcess\Infrastructure\Adapter\InventoryApiInterface;

/**
 * Class constructor.
 *
 * @param PrivateQueryInteractor $privateQueryInteractor the instance of the PrivateQueryInteractor class
 */
final readonly class InventoryApi implements InventoryApiInterface
{
    /**
     * Class constructor.
     *
     * @param PrivateQueryInteractor $privateQueryInteractor the instance of the PrivateQueryInteractor class
     */
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
        return $this->privateQueryInteractor->findFilms(FilmType::Film->value)->items;
    }
}
