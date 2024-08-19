<?php

declare(strict_types=1);

namespace App\Orders\Domain\DTO;

/**
 * Represents a film data object.
 */
final readonly class FilmData
{
    /**
     * Class constructor.
     *
     * @param int    $id       the unique identifier of the entry
     * @param string $name     the name of the entry
     * @param int    $length   the length of the entry
     * @param string $filmType the roll type of the entry
     */
    public function __construct(public int $id, public string $name, public int $length, public string $filmType)
    {
    }
}
