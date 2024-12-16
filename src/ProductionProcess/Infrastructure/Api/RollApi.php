<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Api;

use App\Inventory\Application\UseCases\Query\DTO\RollData;
use App\Inventory\Infrastructure\Adapter\Rolls\RollsApiInterface;
use App\ProductionProcess\Application\UseCase\PrivateQueryInteractor;

/**
 * Class RollApi.
 *
 * A final readonly class that implements the RollsApiInterface.
 * This class provides a method to get a roll by its ID.
 */
final readonly class RollApi implements RollsApiInterface
{
    /**
     * Class constructor.
     *
     * @param PrivateQueryInteractor $privateQueryInteractor The private query interactor object
     */
    public function __construct(private PrivateQueryInteractor $privateQueryInteractor)
    {
    }

    /**
     * Retrieves a RollData object based on the provided rollId.
     *
     * @param string $rollId The ID of the roll
     *
     * @return RollData Returns a RollData object
     */
    public function getRollById(string $rollId): RollData
    {
        $roll = $this->privateQueryInteractor->findARoll($rollId)->rollData;

        return new RollData(id: $roll->id, length: round($roll->length, 2), filmId: $roll->filmId);
    }
}
