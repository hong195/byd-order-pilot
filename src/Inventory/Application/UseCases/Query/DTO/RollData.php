<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\DTO;

/**
 * Class RollData.
 *
 * Represents roll data.
 */
final readonly class RollData
{
    /**
     * Constructor for a class.
     *
     * @param int $id     The ID of the object
     * @param int $length The length of the object
     * @param int $filmId The film ID associated with the object
     */
    public function __construct(public int $id, public int $length, public int $filmId)
    {
    }
}
