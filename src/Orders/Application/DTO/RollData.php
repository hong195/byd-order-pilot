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
     * @param array                   $films       an array of films associated with the object
     * @param array                   $laminations An array of laminations associated with the object. (optional)
     * @param \DateTimeInterface|null $dateAdded   The date the object was added. (optional)
     */
    public function __construct(public int $id, public string $name, public int $length, public array $films, public array $laminations = [], public ?\DateTimeInterface $dateAdded = null)
    {
    }
}
