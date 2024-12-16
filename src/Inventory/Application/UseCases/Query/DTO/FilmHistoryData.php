<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\DTO;

/**
 * Represents a film history data instance.
 */
final readonly class FilmHistoryData
{
    /**
     * Class representing an instance of an inventory.
     */
    public function __construct(
        public string $id,
        public int $filmId,
        public string $inventoryType,
        public string $filmType,
        public string $event,
        public string $createdAt,
        public float $newSize = 0,
        public float $oldSize = 0,
    ) {
    }
}
