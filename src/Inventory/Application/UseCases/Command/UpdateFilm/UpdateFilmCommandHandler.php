<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Command\UpdateFilm;

use App\Inventory\Domain\Aggregate\FilmType;
use App\Inventory\Domain\Service\FilmUpdater;
use App\Inventory\Domain\Service\LaminationUpdater;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateFilmCommandHandler handles updating a Film entity.
 */
readonly class UpdateFilmCommandHandler implements CommandHandlerInterface
{
    /**
     * Class Description: This class represents a constructor of a Symfony application. It takes two private dependencies: an instance of the AccessControlService class and an instance of the FilmUpdater class.
     *
     * @param AccessControlService $accessControlService an instance of the AccessControlService class used for controlling access to the application
     */
    public function __construct(private AccessControlService $accessControlService, private FilmUpdater $filmUpdater, private LaminationUpdater $laminationUpdater)
    {
    }

    /**
     * Update a Film entity.
     *
     * @param UpdateFilmCommand $updateFilmCommand The command to update the Film entity
     *
     * @throws NotFoundHttpException if the Film entity is not found
     */
    public function __invoke(UpdateFilmCommand $updateFilmCommand): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to add lamination.');

        match ($updateFilmCommand->filmType) {
            FilmType::LAMINATION->value => $this->laminationUpdater->update(id: $updateFilmCommand->id, name: $updateFilmCommand->name, length: $updateFilmCommand->length, type: $updateFilmCommand->type),
            FilmType::Film->value => $this->filmUpdater->update(id: $updateFilmCommand->id, name: $updateFilmCommand->name, length: $updateFilmCommand->length, type: $updateFilmCommand->type),
            default => throw new \InvalidArgumentException('Invalid film type'),
        };
    }
}
