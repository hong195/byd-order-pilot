<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Command\DeleteFilm;

use App\Inventory\Infrastructure\Repository\FilmRepository;
use App\Shared\Application\Command\CommandHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DeleteFilmCommandHandler.
 */
readonly class DeleteFilmCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param FilmRepository $filmRepository the FilmRepository instance
     */
    public function __construct(private FilmRepository $filmRepository, private EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * Handle the delete Film command.
     *
     * @param DeleteFilmCommand $deleteFilmCommand the delete Film command object
     *
     * @throws NotFoundHttpException if Film not found
     */
    public function __invoke(DeleteFilmCommand $deleteFilmCommand): void
    {
        $film = $this->filmRepository->findById($deleteFilmCommand->id);

        if (is_null($film)) {
            throw new NotFoundHttpException('Film not found');
        }

        $this->filmRepository->remove($film);
    }
}
