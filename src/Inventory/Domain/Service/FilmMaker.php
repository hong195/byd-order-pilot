<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Service;

use App\Inventory\Domain\Aggregate\AbstractFilm;
use App\Inventory\Domain\Aggregate\FilmType;
use App\Inventory\Domain\Factory\FilmFactory;
use App\Inventory\Infrastructure\Repository\FilmRepository;

final readonly class FilmMaker
{
    public function __construct(private FilmRepository $filmRepository, private FilmFactory $filmFactory)
    {
    }

    public function make(string $name, int $length, string $filmType, string $type): AbstractFilm
    {
        $film = $this->filmFactory->make($name, $length, FilmType::from($filmType), $type);

        $this->filmRepository->save($film);

        return $film;
    }
}
