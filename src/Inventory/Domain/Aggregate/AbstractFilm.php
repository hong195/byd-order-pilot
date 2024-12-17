<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Aggregate;

use App\Inventory\Domain\Events\FilmWasCreatedEvent;
use App\Inventory\Domain\Events\FilmWasUpdatedEvent;
use App\Inventory\Domain\Events\FilmWasUsedEvent;
use App\Inventory\Domain\Exceptions\NotEnoughFilmException;
use App\Shared\Domain\Service\UlidService;

/**
 * Class AbstractFilm.
 *
 * This class represents a roll.
 */
abstract class AbstractFilm extends AggregateRoot
{
    private string $id;

    private \DateTimeImmutable $dateAdded;

    /**
     * Class Constructor.
     *
     * @param string $name   the name of the object
     * @param float  $length the length of the object
     *
     * @return void
     */
    public function __construct(protected string $name, protected float $length, protected string $type)
    {
        $this->id = UlidService::generate();
        $this->dateAdded = new \DateTimeImmutable();

        $this->raise(new FilmWasCreatedEvent($this->getId()));
    }

    /**
     * Get the ID of the object.
     *
     * @return string the ID of the object
     */
    public function getId(): string
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
            $this->raise(new FilmWasUpdatedEvent(filmId: $this->id, newSize: $newLength, oldSize: $this->length, filmType: $this->type));
        }

        $this->length = $newLength;
    }

    /**
     * @throws NotEnoughFilmException
     */
    public function use(float $lengthToUse): void
    {
        if ($lengthToUse > $this->length) {
            throw new NotEnoughFilmException('Not Enough Film');
        }

        $this->raise(new FilmWasUsedEvent(filmId: $this->id, newSize: $lengthToUse, oldSize: $this->length, filmType: $this->type));

        $this->length -= $lengthToUse;
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
