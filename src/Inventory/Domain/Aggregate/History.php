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

    /**
     * Class Constructor.
     *
     * @param string    $filmType      the film type
     * @param string    $eventType     the event type
     * @param float|int $changeAmount  the change amount
     * @param float|int $remainingSize the remaining size
     */
    public function __construct(
        public readonly int $filmId,
        public readonly string $filmType,
        public readonly string $eventType,
        public readonly float|int $changeAmount,
        public readonly float|int $remainingSize,
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
}
