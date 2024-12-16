<?php

declare(strict_types=1);

namespace App\Inventory\Domain\Repository;

use App\Shared\Domain\Repository\Pager;

/**
 * Class FilmFilter.
 *
 * Represents a filter for films.
 */
final readonly class HistoryFilter
{
    /**
     * Constructor for the class.
     *
     * @param Pager|null           $pager         the pager object (default: null)
     * @param string|null          $inventoryType the inventory type (default: null)
     * @param int|null             $filmId        the film ID (default: null)
     * @param string|null          $event         the event (default: null)
     * @param \DateTimeInterface[] $interval      the interval (default: empty array)
     */
    public function __construct(
        public ?Pager $pager = null,
        public ?string $inventoryType = null,
        public ?string $filmId = null,
        public ?string $event = null,
        public ?string $type = null,
        public ?array $interval = [],
    ) {
    }
}
