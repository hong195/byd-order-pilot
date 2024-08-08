<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases;

use App\Inventory\Application\UseCases\Command\AddFilm\AddFilmCommand;
use App\Inventory\Application\UseCases\Command\DeleteFilm\DeleteFilmCommand;
use App\Inventory\Application\UseCases\Command\UpdateFilm\UpdateFilmCommand;
use App\Inventory\Domain\Aggregate\FilmType;
use App\Shared\Application\Command\CommandBusInterface;

/**
 * Class PrivateCommandInteractor.
 */
readonly class PrivateCommandInteractor
{
    /**
     * Class ExampleClass.
     */
    public function __construct(
        private CommandBusInterface $commandBus
    ) {
    }

    public function addARollFilm(string $name, int $length, string $type): int
    {
        $command = new AddFilmCommand($name, $length, FilmType::ROLL->value, $type);

        return $this->commandBus->execute($command);
    }

    public function addALaminationFilm(string $name, int $length, string $type): int
    {
        $command = new AddFilmCommand($name, $length, FilmType::LAMINATION->value, $type);

        return $this->commandBus->execute($command);
    }

    public function updateFilm(int $id, string $name, int $length): void
    {
        $command = new UpdateFilmCommand($id, $name, $length);
        $this->commandBus->execute($command);
    }

    public function deleteFilm(int $id): void
    {
        $command = new DeleteFilmCommand($id);

        $this->commandBus->execute($command);
    }
}
