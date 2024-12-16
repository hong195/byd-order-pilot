<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\DTO;

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
     * @param float    $length   the length of the entry
     * @param string $filmType the roll type of the entry
     */
    public function __construct(public string $id, public string $name, public float $length, public string $filmType)
    {
    }
}
