<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Service;

use App\Inventory\Domain\Aggregate\AbstractFilm;
use App\Inventory\Domain\Aggregate\FilmType;
use App\Inventory\Domain\Events\FilmWasCreatedEvent;
use App\Inventory\Domain\Factory\FilmFactory;
use App\Inventory\Infrastructure\Repository\FilmRepository;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class FilmMaker
{
    /**
     * Class constructor.
     *
     * @param FilmRepository           $filmRepository  the film repository object
     * @param FilmFactory              $filmFactory     the film factory object
     * @param EventDispatcherInterface $eventDispatcher the event dispatcher interface object
     */
    public function __construct(private FilmRepository $filmRepository, private FilmFactory $filmFactory, private EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * Creates a new film.
     *
     * @param string $name     the name of the film
     * @param int    $length   the length of the film
     * @param string $filmType the type of the film
     * @param string $type     the specific type of the film
     *
     * @return AbstractFilm the newly created film
     *
     * @throws \InvalidArgumentException when an invalid film type is provided
     */
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

        $this->eventDispatcher->dispatch(new FilmWasCreatedEvent($film->getId()));

        return $film;
    }
}
