<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Command\UseFilm;

use App\Inventory\Domain\Exceptions\NotEnoughFilmException;
use App\Inventory\Domain\Repository\FilmRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * Class UseFilmCommandHandler.
 */
readonly class UseFilmCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param FilmRepositoryInterface $filmRepository the film repository to be injected
     */
    public function __construct(private FilmRepositoryInterface $filmRepository)
    {
    }

	/**
	 * @throws NotEnoughFilmException
	 */
	public function __invoke(UseFilmCommand $command): void
    {
        $film = $this->filmRepository->findById($command->filmId);

        $film->use($command->length);
    }
}
