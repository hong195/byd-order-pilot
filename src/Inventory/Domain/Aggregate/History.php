<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Aggregate;

use App\Shared\Domain\Service\UlidService;

final class History
{
    /*
     * @php-stan-ignore-next-line
     */
    public string $id;
    private \DateTimeImmutable $createdAt;
    /**
     * Class constructor.
     *
     * @param int    $filmId        The film ID
     * @param string $inventoryType The inventory type
     * @param string $filmType      The film type
     * @param string $eventType     The event type
     * @param float  $newSize       The new size
     * @param float  $oldSize       The old size
     */
    public function __construct(
        public readonly int $filmId,
        public string $inventoryType,
        public readonly string $filmType,
        public readonly string $eventType,
        public readonly float $newSize,
        public readonly float $oldSize,
    ) {
		$this->id = UlidService::generate();
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Get the ID of the object.
     *
     * @return string The ID of the object or null if not set
     */
    public function getId(): string
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
