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
     * Constructor for the class.
     *
     * @param int                     $id            the ID of the object
     * @param string                  $name          the name of the object
     * @param int                     $length        the length of the object
     * @param int                     $count         the count of the object
     * @param array                   $films         an array of films
     * @param string|null             $process       the process of the object (optional)
     * @param int                     $priorityCount the priority count of the object (default: 0)
     * @param array                   $laminations   an array of laminations
     * @param int|null                $filmId        the film ID of the object (optional)
     * @param int|null                $printerId     the printer ID of the object (optional)
     * @param \DateTimeInterface|null $dateAdded     the date added of the object (optional)
     */
    public function __construct(
        public int $id, public string $name, public int $length, public int $count,
        public array $films, public ?string $process = null, public int $priorityCount = 0, public array $laminations = [], public ?int $filmId = null, public ?int $printerId = null,
        public ?\DateTimeInterface $dateAdded = null)
    {
    }
}
