<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Aggregate;

use App\Inventory\Domain\Events\FilmLengthWasUpdatedEvent;
use App\Inventory\Domain\Events\FilmWasUsedEvent;
use App\Shared\Domain\Aggregate\Aggregate;

/**
 * Class AbstractFilm.
 *
 * This class represents a roll.
 */
abstract class AbstractFilm extends Aggregate
{
    /**
     * @phpstan-ignore-next-line
     */
    private int $id;

    private \DateTimeImmutable $dateAdded;

    /**
     * Class Constructor.
     *
     * @param string $name   the name of the object
     * @param float    $length the length of the object
     *
     * @return void
     */
    public function __construct(protected string $name, protected float $length, protected string $type)
    {
        $this->dateAdded = new \DateTimeImmutable();
    }

    /**
     * Get the ID of the object.
     *
     * @return int the ID of the object
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the name of the object.
     *
     * @return string the name of the object
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Change the name of the object.
     *
     * @param string $name the new name for the object
     */
    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the length of the object.
     *
     * @return float the length of the object
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * Updates the length of the object.
     *
     * @param float $newLength the new length of the object
     */
    public function updateLength(float $newLength): void
    {
		if ($this->length !== $newLength) {
			$this->raise(new FilmLengthWasUpdatedEvent(filmId: $this->id, newSize: $newLength, oldSize:  $this->length));
		}

        $this->length = $newLength;
    }

    /**
     * Get the type of the object.
     *
     * @return string the type of the object
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the type of the object.
     *
     * @param string $type the type of the object
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Get the date that the object was added.
     *
     * @return \DateTimeImmutable the date that the object was added
     */
    public function getDateAdded(): \DateTimeImmutable
    {
        return $this->dateAdded;
    }
}
