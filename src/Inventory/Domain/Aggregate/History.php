<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Aggregate;

final class History
{
    /*
     * @phpstan-ignore-next-line
     */
    public ?int $id;
    private \DateTimeImmutable $createdAt;

    public float $difference = 0;

    /**
     * Class Constructor.
     *
     * @param int    $filmId    the ID of the film
     * @param string $filmType  the type of the film
     * @param string $eventType the type of the event
     * @param float  $newSize   the new size of the film
     * @param float  $oldSize   the old size of the film
     */
    public function __construct(
        public readonly int $filmId,
        public readonly string $filmType,
        public readonly string $eventType,
        public readonly float $newSize,
        public readonly float $oldSize,
    ) {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Get the ID of the object.
     *
     * @return int|null The ID of the object or null if not set
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the created at date and time.
     *
     * @return \DateTimeImmutable the created at date and time
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Get the difference between the new size and the old size.
     *
     * @return float the difference between the new size and the old size
     */
    public function getDifference(): float
    {
        return $this->newSize - $this->oldSize;
    }
}
