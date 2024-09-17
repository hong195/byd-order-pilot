<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases;

use App\Inventory\Application\UseCases\Command\AddFilm\AddFilmCommand;
use App\Inventory\Application\UseCases\Command\RecordInventoryAdding\RecordInventoryAddingCommand;
use App\Inventory\Application\UseCases\Command\DeleteFilm\DeleteFilmCommand;
use App\Inventory\Application\UseCases\Command\RecordInventoryUpdating\RecordInventoryUpdatingCommand;
use App\Inventory\Application\UseCases\Command\UpdateFilm\UpdateFilmCommand;
use App\Inventory\Domain\Aggregate\FilmType;
use App\Shared\Application\Command\CommandBusInterface;

/**
 * Class PrivateCommandInteractor.
 */
readonly class PrivateCommandInteractor
{
    /**
     * Constructs a new instance of the class.
     *
     * @param CommandBusInterface $commandBus the command bus to be used
     */
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    /**
     * Adds a film to the application.
     *
     * @param string $name   the name of the film
     * @param int    $length the length of the film
     * @param string $type   the type of the film
     *
     * @return int the result of the command execution
     */
    public function addFilm(string $name, int $length, string $type): int
    {
        $command = new AddFilmCommand($name, $length, FilmType::Film->value, $type);

        return $this->commandBus->execute($command);
    }

    /**
     * Adds a lamination to the application.
     *
     * @param string $name   the name of the lamination
     * @param int    $length the length of the lamination
     * @param string $type   the type of the lamination
     *
     * @return int the result of the command execution
     */
    public function addALamination(string $name, int $length, string $type): int
    {
        $command = new AddFilmCommand($name, $length, FilmType::LAMINATION->value, $type);

        return $this->commandBus->execute($command);
    }

    /**
     * Updates a film.
     *
     * @param int         $id     the ID of the film
     * @param string      $name   the new name of the film
     * @param float         $length the new length of the film
     * @param string|null $type   the new type of the film (optional)
     */
    public function updateFilm(int $id, string $name, float $length, ?string $type = null): void
    {
        $command = new UpdateFilmCommand(id: $id, filmType: FilmType::Film->value, name: $name, length: $length, type: $type);
        $this->commandBus->execute($command);
    }

    /**
     * Update the lamination of a film.
     *
     * @param int         $id     the ID of the film
     * @param string      $name   the name of the film
     * @param int         $length the length of the film
     * @param string|null $type   The type of the film. Optional.
     */
    public function updateLamination(int $id, string $name, int $length, ?string $type = null): void
    {
        $command = new UpdateFilmCommand(id: $id, filmType: FilmType::LAMINATION->value, name: $name, length: $length, type: $type);
        $this->commandBus->execute($command);
    }

    /**
     * Delete a film by ID.
     *
     * @param int $id the ID of the film to be deleted
     */
    public function deleteFilm(int $id): void
    {
        $command = new DeleteFilmCommand($id);

        $this->commandBus->execute($command);
    }

	/**
	 * Records the addition of inventory to the application.
	 *
	 * @param int $filmId the ID of the film for which inventory is being added
	 * @param string $event the event description for the inventory addition
	 *
	 * @return void
	 */
	public function recordInventoryAdding(int $filmId, string $event): void
    {
        $this->commandBus->execute(new RecordInventoryAddingCommand($filmId, $event));
    }

	/**
	 * Records an inventory updating.
	 *
	 * @param RecordInventoryUpdatingCommand $command the command to record the inventory updating
	 *
	 * @return void
	 */
	public function recordInventoryUpdating(RecordInventoryUpdatingCommand $command): void
    {
        $this->commandBus->execute($command);
    }
}
