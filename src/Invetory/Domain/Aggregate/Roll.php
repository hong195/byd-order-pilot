<?php

declare(strict_types=1);

namespace App\Invetory\Domain\Aggregate;

/**
 * Class Roll.
 *
 * This class represents a roll.
 */
final class Roll
{
    /**
     * @phpstan-ignore-next-line
     */
    private int $id;

    private \DateTimeImmutable $dateAdded;

    /**
     * Class Constructor.
     *
     * @param string   $name     the name of the object
     * @param int      $length   the length of the object
     * @param RollType $rollType the type of the object
     *
     * @return void
     */
    public function __construct(private string $name, private int $length, public readonly RollType $rollType)
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
     * @return int the length of the object
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * Updates the length of the object.
     *
     * @param int $length the new length of the object
     */
    public function updateLength(int $length): void
    {
        $this->length = $length;
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
