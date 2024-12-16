<?php

namespace App\Inventory\Infrastructure\Adapter\Rolls;

use App\Inventory\Application\UseCases\Query\DTO\RollData;

/**
 * Class RollsApiAdapter.
 *
 * This class provides a way to interact with the Rolls API.
 */
final readonly class RollsApiAdapter
{
    /**
     * @param RollsApiInterface $api The RollsApiAdapter object used for communication with the API
     */
    public function __construct(private RollsApiInterface $api)
    {
    }

    /**
     * Retrieves a RollData object by its ID.
     *
     * @param string $rollId The ID of the roll to retrieve
     *
     * @return RollData The RollData object representing the retrieved roll
     */
    public function getRollById(string $rollId): RollData
    {
        return $this->api->getRollById($rollId);
    }
}
