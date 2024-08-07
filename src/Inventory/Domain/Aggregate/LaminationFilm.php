<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Aggregate;

/**
 * Class LaminationFilm.
 *
 * This class represents a lamination film, extending the AbstractFilm class.
 */
final class LaminationFilm extends AbstractFilm
{
    /**
     * Class constructor.
     *
     * @param string $name   the name of the film
     * @param int    $length the length of the film in minutes
     * @param string $type   the type of the film
     */
    public function __construct(string $name, int $length, string $type)
    {
        parent::__construct($name, $length, FilmType::LAMINATION, $type);
    }
}
