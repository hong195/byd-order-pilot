<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Factory;

use App\Inventory\Domain\Aggregate\AbstractFilm;
use App\Inventory\Domain\Aggregate\FilmType;
use App\Inventory\Domain\Aggregate\LaminationFilm;
use App\Inventory\Domain\Aggregate\RollFilm;

final readonly class FilmFactory
{
    /**
     * Creates a new Film object based on the given parameters.
     *
     * @param string   $name     the name of the film
     * @param float    $length   the length of the film
     * @param FilmType $filmType the type of the film (FilmType enum object)
     * @param string   $type     the type of the film
     *
     * @return AbstractFilm the created Film object
     */
    public function make(string $name, float $length, FilmType $filmType, string $type): AbstractFilm
    {
        if ($filmType->value === FilmType::Film->value) {
            return new RollFilm($name, $length, $type);
        }

        return new LaminationFilm($name, $length, $type);
    }
}
