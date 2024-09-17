<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\DTO;

/**
 * Class FilmData.
 *
 * @readonly
 */
final readonly class RecordHistoryUpdatingData
{
    /**
     * Class Constructor.
     *
     * @param int    $filmId  the ID of the film
     * @param string $event   the event associated with the film
     * @param float  $oldSize the old size of the film
     * @param float  $newSize the new size of the film
     */
    public function __construct(public int $filmId, public string $event, public float $oldSize, public float $newSize)
    {
    }
}
