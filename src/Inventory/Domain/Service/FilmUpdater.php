<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Service;

use App\Inventory\Infrastructure\Repository\FilmRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Represents a FilmUpdater class responsible for updating film data.
 */
final readonly class FilmUpdater
{
    /**
     * Initializes a new instance of the class.
     *
     * @param FilmRepository $filmRepository the instance of the FilmRepository
     */
    public function __construct(private FilmRepository $filmRepository)
    {
    }

    /**
     * Updates the film with the given id, changing its name and length if necessary.
     *
     * @param int    $id     the id of the film to update
     * @param string $name   the new name for the film
     * @param int    $length the new length for the film
     *
     * @throws NotFoundHttpException if the film with the given id is not found
     */
    public function update(int $id, string $name, int $length): void
    {
        $film = $this->filmRepository->findById($id);

        if (is_null($film)) {
            throw new NotFoundHttpException('Film not found');
        }

        if ($name !== $film->getName()) {
            $film->changeName($name);
        }

        if ($length !== $film->getLength()) {
            $film->updateLength($length);
        }

        $this->filmRepository->save($film);
    }
}
