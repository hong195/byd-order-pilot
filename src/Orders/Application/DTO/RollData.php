<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

/**
 * Class RollData.
 *
 * Represents a roll of data.
 */
final readonly class RollData
{
    /**
     * @param int                     $id            the ID of the object
     * @param string                  $name          the name of the object
     * @param int                     $length        the length of the object
     * @param int                     $count         the count of the object
     * @param int                     $priorityCount The priority count of the object. Defaults to 0.
     * @param string[]                $films         the array of films associated with the object
     * @param string[]                $laminations   The array of laminations associated with the object. Defaults to an empty array.
     * @param int|null                $filmId        The film ID associated with the object. Defaults to null.
     * @param int|null                $printerId     The printer ID associated with the object. Defaults to null.
     * @param \DateTimeInterface|null $dateAdded     The date added of the object. Defaults to null.
     */
    public function __construct(
        public int $id, public string $name, public int $length, public int $count,
        public array $films, public int $priorityCount = 0, public array $laminations = [], public ?int $filmId = null, public ?int $printerId = null,
        public ?\DateTimeInterface $dateAdded = null)
    {
    }
}
