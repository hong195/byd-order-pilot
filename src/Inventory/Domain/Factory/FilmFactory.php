<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Factory;

use App\Inventory\Domain\Aggregate\AbstractFilm;
use App\Inventory\Domain\Aggregate\FilmType;
use App\Inventory\Domain\Aggregate\LaminationFilm;
use App\Inventory\Domain\Aggregate\RollFilm;

final readonly class FilmFactory
{
    public function make(string $name, int $length, FilmType $filmType, string $type): AbstractFilm
    {
        if ($filmType->value === FilmType::Film->value) {
            return new RollFilm($name, $length, $type);
        } else {
            return new LaminationFilm($name, $length, $type);
        }
    }
}
