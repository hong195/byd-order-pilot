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
     * Constructor method for initializing an object.
     *
     * @param int                     $id          the ID of the object
     * @param string                  $name        the name of the object
     * @param int                     $length      the length of the object
     * @param int                     $count       the count of the object
     * @param string[]                $films       the films associated with the object
     * @param string[]                $laminations The laminations associated with the object. Defaults to an empty array.
     * @param \DateTimeInterface|null $dateAdded   The date the object was added. Defaults to null.
     */
    public function __construct(public int $id, public string $name, public int $length, public int $count, public array $films, public array $laminations = [], public ?\DateTimeInterface $dateAdded = null)
    {
    }
}
