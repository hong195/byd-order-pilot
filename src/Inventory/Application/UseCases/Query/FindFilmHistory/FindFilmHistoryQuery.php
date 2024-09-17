<?php

declare(strict_types=1);

namespace App\Inventory\Application\UseCases\Query\FindFilmHistory;

use App\Shared\Application\Query\QueryInterface;

final class FindFilmHistoryQuery implements QueryInterface
{
    /**
     * @var \DateTimeInterface[] an array to hold interval values
     */
    public ?array $interval = [];

    /**
     * Class constructor.
     *
     * @param string|null $inventoryType the inventory type
     * @param int|null    $filmId        the film ID
     * @param string|null $event         the event
     * @param string|null $type          the type
     * @param int|null    $page          the page number
     * @param int|null    $perPage       the number of items per page
     */
    public function __construct(
        public readonly ?string $inventoryType = null,
        public readonly ?int $filmId = null,
        public readonly ?string $event = null,
        public readonly ?string $type = null,
        public readonly ?int $page = null,
        public readonly ?int $perPage = null,
    ) {
    }

    /**
     * Sets the interval.
     *
     * @param \DateTimeInterface[] $interval the interval
     */
    public function withInterval(array $interval): void
    {
        $this->interval = $interval;
    }
}
