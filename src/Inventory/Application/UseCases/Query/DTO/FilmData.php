<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\DTO;

/**
 * Class FilmData.
 *
 * @readonly
 */
final readonly class FilmData
{
    /**
     * Class constructor.
     *
     * @param string $id     the unique identifier
     * @param string $name   the name of the object
     * @param float  $length the length of the object
     * @param string $type   the type of the object
     */
    public function __construct(public string $id, public string $name, public float $length, public string $type)
    {
    }
}
