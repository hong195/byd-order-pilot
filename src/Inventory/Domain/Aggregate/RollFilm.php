<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Aggregate;

/**
 * Represents a RollFilm extending the AbstractFilm class.
 */
final class RollFilm extends AbstractFilm
{
    /**
     * Class constructor.
     *
     * @param string $name   the name of the film
     * @param int    $length the length of the film
     * @param string $type   the type of the film
     *
     * @return void
     */
    public function __construct(string $name, int $length, string $type)
    {
        parent::__construct($name, $length, FilmType::ROLL, $type);
    }
}
