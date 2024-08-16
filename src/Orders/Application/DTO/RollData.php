<?php

namespace App\Orders\Application\DTO;

/**
 * Class RollData.
 *
 * Represents a roll of data.
 */
final readonly class RollData
{
    /**
     * Class constructor.
     *
     * @param int                     $id          the ID of the object
     * @param string                  $name        the name of the object
     * @param int                     $length      the length of the object
     * @param int                     $count       the count of the object
     * @param array                   $films       an array of films
     * @param array                   $laminations an optional array of laminations
     * @param int|null                $filmId      The ID of the film. Default is null.
     * @param int|null                $printerId   The ID of the printer. Default is null.
     * @param \DateTimeInterface|null $dateAdded   The date when the object was added. Default is null.
     */
    public function __construct(public int $id, public string $name, public int $length, public int $count,
        public array $films, public array $laminations = [], public ?int $filmId = null, public ?int $printerId = null, public ?\DateTimeInterface $dateAdded = null)
    {
    }
}
