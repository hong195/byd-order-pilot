<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Command\AddFilm;

use App\Inventory\Domain\Service\FilmMaker;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

readonly class AddFilmCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private AccessControlService $accessControlService,
        private FilmMaker $filmMaker,
    ) {
    }

    /**
     * Invokes the AddFilmCommand handler.
     *
     * @param AddFilmCommand $addFilmCommand the command object containing the Film details
     *
     * @return int the ID of the added Film
     */
    public function __invoke(AddFilmCommand $addFilmCommand): int
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to handle resource.');

        $film = $this->filmMaker->make($addFilmCommand->name, $addFilmCommand->length, $addFilmCommand->filmType, $addFilmCommand->type);

        return $film->getId();
    }
}
