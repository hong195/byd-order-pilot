<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Command\DeleteFilm;

use App\Inventory\Infrastructure\Repository\FilmRepository;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DeleteFilmCommandHandler.
 */
readonly class DeleteFilmCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param FilmRepository       $filmRepository       the FilmRepository instance
     * @param AccessControlService $accessControlService the AccessControlService instance
     */
    public function __construct(
        private FilmRepository $filmRepository,
        private AccessControlService $accessControlService,
    ) {
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
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to remove film');
        $Film = $this->filmRepository->findById($deleteFilmCommand->id);

        if (is_null($Film)) {
            throw new NotFoundHttpException('Film not found');
        }

        $this->filmRepository->remove($Film);
    }
}
