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

        if ($filmType === FilmType::Film->value) {
            if (!in_array($type, ['chrome', 'neon', 'white', 'clear', 'eco'])) {
                throw new \InvalidArgumentException('Invalid film type');
            }
        }

        if ($filmType === FilmType::LAMINATION->value) {
            if (!in_array($type, ['holo_flakes', 'matt', 'glossy', 'gold_flakes'])) {
                throw new \InvalidArgumentException('Invalid film type');
            }
        }
        $this->filmRepository->save($film);

        return $film;
    }
}
